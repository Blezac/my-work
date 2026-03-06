<?php

// app/Services/AccountingService.php
namespace App\Services;

use App\Models\Journal;
use App\Models\JournalLine;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\Expense;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Exception;

class AccountingService
{
    /**
     * Create a journal entry with double-entry validation
     */
    public function createJournal(array $data): Journal
    {
        return DB::transaction(function () use ($data) {
            $journal = Journal::create([
                'company_id' => $data['company_id'],
                'journal_number' => $this->generateJournalNumber($data['company_id']),
                'date' => $data['date'],
                'type' => $data['type'],
                'reference_id' => $data['reference_id'] ?? null,
                'reference_type' => $data['reference_type'] ?? null,
                'description' => $data['description'] ?? null,
                'created_by' => auth()->id(),
                'is_posted' => false,
            ]);

            $totalDebit = 0;
            $totalCredit = 0;

            foreach ($data['lines'] as $line) {
                $journalLine = JournalLine::create([
                    'journal_id' => $journal->id,
                    'account_id' => $line['account_id'],
                    'debit' => $line['debit'] ?? 0,
                    'credit' => $line['credit'] ?? 0,
                    'description' => $line['description'] ?? null,
                ]);

                $totalDebit += $journalLine->debit;
                $totalCredit += $journalLine->credit;
            }

            // CRITICAL: Validate double-entry
            if (bccomp($totalDebit, $totalCredit, 2) !== 0) {
                throw new Exception('Journal entry is not balanced. Debit: ' . $totalDebit . ', Credit: ' . $totalCredit);
            }

            $journal->update([
                'total_debit' => $totalDebit,
                'total_credit' => $totalCredit,
            ]);

            return $journal->load('lines.account');
        });
    }

    /**
     * Post a journal entry (make it permanent)
     */
    public function postJournal(Journal $journal): Journal
    {
        if ($journal->is_posted) {
            throw new Exception('Journal entry is already posted');
        }

        if (!$journal->isBalanced()) {
            throw new Exception('Cannot post unbalanced journal entry');
        }

        $journal->update([
            'is_posted' => true,
            'posted_at' => now(),
        ]);

        return $journal;
    }

    /**
     * Post an invoice and create journal entries
     * Debit: Accounts Receivable
     * Credit: Revenue
     * Credit: Tax Payable (if applicable)
     */
    public function postInvoice(Invoice $invoice): Journal
    {
        if ($invoice->journal_id) {
            throw new Exception('Invoice already posted');
        }

        $arAccount = $this->getSystemAccount($invoice->company_id, 'accounts_receivable');
        $revenueAccount = $this->getSystemAccount($invoice->company_id, 'sales_revenue');
        $taxAccount = $this->getSystemAccount($invoice->company_id, 'tax_payable');

        $lines = [
            [
                'account_id' => $arAccount->id,
                'debit' => $invoice->total,
                'credit' => 0,
                'description' => 'Invoice #' . $invoice->invoice_number,
            ],
            [
                'account_id' => $revenueAccount->id,
                'debit' => 0,
                'credit' => $invoice->subtotal,
                'description' => 'Sales revenue',
            ],
        ];

        if ($invoice->tax_amount > 0) {
            $lines[] = [
                'account_id' => $taxAccount->id,
                'debit' => 0,
                'credit' => $invoice->tax_amount,
                'description' => 'Sales tax',
            ];
        }

        $journal = $this->createJournal([
            'company_id' => $invoice->company_id,
            'date' => $invoice->invoice_date,
            'type' => 'sales',
            'reference_id' => $invoice->id,
            'reference_type' => Invoice::class,
            'description' => 'Invoice #' . $invoice->invoice_number,
            'lines' => $lines,
        ]);

        $this->postJournal($journal);

        $invoice->update(['journal_id' => $journal->id]);

        return $journal;
    }

    /**
     * Post an expense
     * Debit: Expense Account
     * Credit: Cash/Bank Account
     */
    public function postExpense(Expense $expense): Journal
    {
        if ($expense->journal_id) {
            throw new Exception('Expense already posted');
        }

        $lines = [
            [
                'account_id' => $expense->expense_account_id,
                'debit' => $expense->total,
                'credit' => 0,
                'description' => $expense->description,
            ],
            [
                'account_id' => $expense->payment_account_id,
                'debit' => 0,
                'credit' => $expense->total,
                'description' => 'Payment for expense',
            ],
        ];

        $journal = $this->createJournal([
            'company_id' => $expense->company_id,
            'date' => $expense->expense_date,
            'type' => 'purchase',
            'reference_id' => $expense->id,
            'reference_type' => Expense::class,
            'description' => 'Expense #' . $expense->expense_number,
            'lines' => $lines,
        ]);

        $this->postJournal($journal);

        $expense->update(['journal_id' => $journal->id]);

        return $journal;
    }

    /**
     * Post a payment received
     * Debit: Cash/Bank
     * Credit: Accounts Receivable
     */
    public function postPayment(Payment $payment): Journal
    {
        if ($payment->journal_id) {
            throw new Exception('Payment already posted');
        }

        $cashAccount = $this->getSystemAccount($payment->company_id, 'cash');
        $arAccount = $this->getSystemAccount($payment->company_id, 'accounts_receivable');

        $lines = [
            [
                'account_id' => $cashAccount->id,
                'debit' => $payment->amount,
                'credit' => 0,
                'description' => 'Payment received #' . $payment->payment_number,
            ],
            [
                'account_id' => $arAccount->id,
                'debit' => 0,
                'credit' => $payment->amount,
                'description' => 'Payment for Invoice #' . ($payment->invoice->invoice_number ?? 'N/A'),
            ],
        ];

        $journal = $this->createJournal([
            'company_id' => $payment->company_id,
            'date' => $payment->payment_date,
            'type' => 'receipt',
            'reference_id' => $payment->id,
            'reference_type' => Payment::class,
            'description' => 'Payment #' . $payment->payment_number,
            'lines' => $lines,
        ]);

        $this->postJournal($journal);

        $payment->update(['journal_id' => $journal->id]);

        // Update invoice status
        if ($payment->invoice) {
            $this->updateInvoiceStatus($payment->invoice);
        }

        return $journal;
    }

    /**
     * Update invoice status based on payments
     */
    protected function updateInvoiceStatus(Invoice $invoice): void
    {
        $totalPaid = $invoice->payments()->sum('amount');
        $invoice->amount_paid = $totalPaid;

        if ($totalPaid >= $invoice->total) {
            $invoice->status = 'paid';
        } elseif ($totalPaid > 0) {
            $invoice->status = 'partial';
        } elseif ($invoice->due_date < now() && $invoice->status !== 'paid') {
            $invoice->status = 'overdue';
        }

        $invoice->save();
    }

    /**
     * Get system account by code
     */
    protected function getSystemAccount(int $companyId, string $code): Account
    {
        $account = Account::where('company_id', $companyId)
            ->where('code', $code)
            ->first();

        if (!$account) {
            throw new Exception("System account '{$code}' not found");
        }

        return $account;
    }

    /**
     * Generate unique journal number
     */
    protected function generateJournalNumber(int $companyId): string
    {
        $prefix = 'JE';
        $date = now()->format('Ymd');
        $count = Journal::where('company_id', $companyId)
            ->whereDate('created_at', now())
            ->count() + 1;

        return sprintf('%s-%s-%04d', $prefix, $date, $count);
    }

    /**
     * Generate trial balance
     */
    public function getTrialBalance(int $companyId, $startDate = null, $endDate = null): array
    {
        $accounts = Account::where('company_id', $companyId)
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        $trialBalance = [];
        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($accounts as $account) {
            $query = $account->journalLines()
                ->whereHas('journal', function ($q) use ($startDate, $endDate) {
                    $q->where('is_posted', true);
                    if ($startDate) {
                        $q->where('date', '>=', $startDate);
                    }
                    if ($endDate) {
                        $q->where('date', '<=', $endDate);
                    }
                });

            $debitSum = $query->sum('debit');
            $creditSum = $query->sum('credit');

            $balance = $debitSum - $creditSum;
            
            if (in_array($account->type, ['asset', 'expense'])) {
                $debitBalance = max(0, $balance);
                $creditBalance = max(0, -$balance);
            } else {
                $debitBalance = max(0, -$balance);
                $creditBalance = max(0, $balance);
            }

            if ($debitBalance != 0 || $creditBalance != 0) {
                $trialBalance[] = [
                    'account_code' => $account->code,
                    'account_name' => $account->name,
                    'account_type' => $account->type,
                    'debit' => $debitBalance,
                    'credit' => $creditBalance,
                ];

                $totalDebit += $debitBalance;
                $totalCredit += $creditBalance;
            }
        }

        return [
            'accounts' => $trialBalance,
            'total_debit' => $totalDebit,
            'total_credit' => $totalCredit,
            'is_balanced' => bccomp($totalDebit, $totalCredit, 2) === 0,
        ];
    }

    /**
     * Generate profit and loss statement
     */
    public function getProfitAndLoss(int $companyId, $startDate, $endDate): array
    {
        $revenue = $this->getAccountTypeTotal($companyId, 'revenue', $startDate, $endDate);
        $expenses = $this->getAccountTypeTotal($companyId, 'expense', $startDate, $endDate);

        return [
            'revenue' => $revenue,
            'expenses' => $expenses,
            'net_profit' => $revenue - $expenses,
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
        ];
    }

    /**
     * Generate balance sheet
     */
    public function getBalanceSheet(int $companyId, $asOfDate): array
    {
        $assets = $this->getAccountTypeTotal($companyId, 'asset', null, $asOfDate);
        $liabilities = $this->getAccountTypeTotal($companyId, 'liability', null, $asOfDate);
        $equity = $this->getAccountTypeTotal($companyId, 'equity', null, $asOfDate);

        return [
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equity' => $equity,
            'total_liabilities_equity' => $liabilities + $equity,
            'as_of_date' => $asOfDate,
            'is_balanced' => bccomp($assets, $liabilities + $equity, 2) === 0,
        ];
    }

    /**
     * Get total for account type
     */
    protected function getAccountTypeTotal(int $companyId, string $type, $startDate = null, $endDate = null): float
    {
        $accounts = Account::where('company_id', $companyId)
            ->where('type', $type)
            ->get();

        $total = 0;

        foreach ($accounts as $account) {
            $query = $account->journalLines()
                ->whereHas('journal', function ($q) use ($startDate, $endDate) {
                    $q->where('is_posted', true);
                    if ($startDate) {
                        $q->where('date', '>=', $startDate);
                    }
                    if ($endDate) {
                        $q->where('date', '<=', $endDate);
                    }
                });

            $debitSum = $query->sum('debit');
            $creditSum = $query->sum('credit');

            if ($type === 'asset' || $type === 'expense') {
                $total += ($debitSum - $creditSum);
            } else {
                $total += ($creditSum - $debitSum);
            }
        }

        return $total;
    }
}