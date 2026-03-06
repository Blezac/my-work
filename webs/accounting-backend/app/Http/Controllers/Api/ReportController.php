<?php
// ============================================
// File: app/Http/Controllers/Api/ReportController.php
// ============================================

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AccountingService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        protected AccountingService $accountingService
    ) {}

    public function trialBalance(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $companyId = $request->user()->company_id;
        
        $trialBalance = $this->accountingService->getTrialBalance(
            $companyId,
            $validated['start_date'] ?? null,
            $validated['end_date'] ?? null
        );

        return response()->json($trialBalance);
    }

    public function profitLoss(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $companyId = $request->user()->company_id;
        
        $profitLoss = $this->accountingService->getProfitAndLoss(
            $companyId,
            $validated['start_date'],
            $validated['end_date']
        );

        return response()->json($profitLoss);
    }

    public function balanceSheet(Request $request)
    {
        $validated = $request->validate([
            'as_of_date' => 'required|date',
        ]);

        $companyId = $request->user()->company_id;
        
        $balanceSheet = $this->accountingService->getBalanceSheet(
            $companyId,
            $validated['as_of_date']
        );

        return response()->json($balanceSheet);
    }

    public function cashFlow(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $companyId = $request->user()->company_id;

        // Simple cash flow calculation
        // You can expand this with proper cash flow statement logic
        $cashAccount = \App\Models\Account::where('company_id', $companyId)
            ->where('code', 'cash')
            ->first();

        if (!$cashAccount) {
            return response()->json([
                'message' => 'Cash account not found'
            ], 404);
        }

        $openingBalance = $cashAccount->opening_balance;
        
        // Get all cash transactions in period
        $transactions = $cashAccount->journalLines()
            ->whereHas('journal', function($q) use ($validated) {
                $q->where('is_posted', true)
                  ->whereBetween('date', [$validated['start_date'], $validated['end_date']]);
            })
            ->with('journal')
            ->get();

        $cashIn = $transactions->sum('debit');
        $cashOut = $transactions->sum('credit');
        $netCashFlow = $cashIn - $cashOut;
        $closingBalance = $openingBalance + $netCashFlow;

        return response()->json([
            'opening_balance' => $openingBalance,
            'cash_in' => $cashIn,
            'cash_out' => $cashOut,
            'net_cash_flow' => $netCashFlow,
            'closing_balance' => $closingBalance,
            'period' => [
                'start' => $validated['start_date'],
                'end' => $validated['end_date'],
            ],
            'transactions' => $transactions->map(fn($line) => [
                'date' => $line->journal->date,
                'description' => $line->description ?? $line->journal->description,
                'debit' => $line->debit,
                'credit' => $line->credit,
            ]),
        ]);
    }
}