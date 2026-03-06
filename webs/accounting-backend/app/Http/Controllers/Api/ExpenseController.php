<?php
// app/Http/Controllers/Api/ExpenseController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Services\AccountingService;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function __construct(
        protected AccountingService $accountingService
    ) {}

    public function index(Request $request)
    {
        $expenses = Expense::where('company_id', $request->user()->company_id)
            ->with('expenseAccount', 'paymentAccount')
            ->latest('expense_date')
            ->paginate(20);

        return response()->json($expenses);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_account_id' => 'required|exists:accounts,id',
            'payment_account_id' => 'required|exists:accounts,id',
            'expense_date' => 'required|date',
            'vendor_name' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'receipt_path' => 'nullable|string',
        ]);

        $expense = Expense::create([
            'company_id' => $request->user()->company_id,
            'expense_number' => $this->generateExpenseNumber($request->user()->company_id),
            'total' => $validated['amount'] + ($validated['tax_amount'] ?? 0),
            ...$validated,
        ]);

        // Auto-post the expense
        $this->accountingService->postExpense($expense);

        return response()->json($expense->load('expenseAccount', 'paymentAccount'), 201);
    }

    protected function generateExpenseNumber(int $companyId): string
    {
        $prefix = 'EXP';
        $year = now()->format('Y');
        $count = Expense::where('company_id', $companyId)
            ->whereYear('created_at', now()->year)
            ->count() + 1;

        return sprintf('%s-%s-%04d', $prefix, $year, $count);
    }

    public function destroy(Expense $expense)
    {
        if ($expense->company_id !== auth()->user()->company_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $expense->delete();
        
        return response()->json(['message' => 'Expense deleted successfully']);
    }
}