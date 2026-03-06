<?php
// app/Http/Controllers/Api/DashboardController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Expense;
use App\Services\AccountingService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected AccountingService $accountingService
    ) {}

    public function index(Request $request)
    {
        $companyId = $request->user()->company_id;
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        // Get cash balance
        $cashAccount = \App\Models\Account::where('company_id', $companyId)
            ->where('code', 'cash')
            ->first();
        
        $cashBalance = $cashAccount ? $cashAccount->balance : 0;

        // Total income this month
        $totalIncome = Invoice::where('company_id', $companyId)
            ->whereBetween('invoice_date', [$startOfMonth, $endOfMonth])
            ->where('status', '!=', 'cancelled')
            ->sum('total');

        // Total expenses this month
        $totalExpenses = Expense::where('company_id', $companyId)
            ->whereBetween('expense_date', [$startOfMonth, $endOfMonth])
            ->sum('total');

        // Outstanding invoices
        $outstandingInvoices = Invoice::where('company_id', $companyId)
            ->whereIn('status', ['sent', 'partial', 'overdue'])
            ->sum('total') - Invoice::where('company_id', $companyId)
            ->whereIn('status', ['sent', 'partial', 'overdue'])
            ->sum('amount_paid');

        // Recent invoices
        $recentInvoices = Invoice::where('company_id', $companyId)
            ->with('customer')
            ->latest('invoice_date')
            ->take(5)
            ->get();

        return response()->json([
            'cash_balance' => $cashBalance,
            'total_income' => $totalIncome,
            'total_expenses' => $totalExpenses,
            'net_income' => $totalIncome - $totalExpenses,
            'outstanding_invoices' => $outstandingInvoices,
            'recent_invoices' => $recentInvoices,
        ]);
    }

    public function charts(Request $request)
    {
        $companyId = $request->user()->company_id;
        
        // Income vs Expenses - Last 6 months
        $months = [];
        $income = [];
        $expenses = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $months[] = $date->format('M Y');

            $income[] = Invoice::where('company_id', $companyId)
                ->whereBetween('invoice_date', [$startOfMonth, $endOfMonth])
                ->where('status', '!=', 'cancelled')
                ->sum('total');

            $expenses[] = Expense::where('company_id', $companyId)
                ->whereBetween('expense_date', [$startOfMonth, $endOfMonth])
                ->sum('total');
        }

        return response()->json([
            'income_vs_expenses' => [
                'labels' => $months,
                'income' => $income,
                'expenses' => $expenses,
            ],
        ]);
    }
}