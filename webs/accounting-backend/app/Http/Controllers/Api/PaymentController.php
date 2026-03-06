<?php
// ============================================
// File: app/Http/Controllers/Api/PaymentController.php
// ============================================

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use App\Services\AccountingService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        protected AccountingService $accountingService
    ) {}

    public function index(Request $request)
    {
        $payments = Payment::where('company_id', $request->user()->company_id)
            ->with('customer', 'invoice')
            ->latest('payment_date')
            ->paginate(20);

        return response()->json($payments);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,check,bank_transfer,credit_card,other',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Get invoice to validate
        $invoice = Invoice::findOrFail($validated['invoice_id']);

        // Ensure invoice belongs to user's company
        if ($invoice->company_id !== $request->user()->company_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check if payment amount is valid
        $remainingBalance = $invoice->total - $invoice->amount_paid;
        if ($validated['amount'] > $remainingBalance) {
            return response()->json([
                'message' => 'Payment amount exceeds invoice balance',
                'remaining_balance' => $remainingBalance,
            ], 422);
        }

        $payment = Payment::create([
            'company_id' => $request->user()->company_id,
            'customer_id' => $invoice->customer_id,
            'payment_number' => $this->generatePaymentNumber($request->user()->company_id),
            ...$validated,
        ]);

        // Auto-post the payment
        $this->accountingService->postPayment($payment);

        return response()->json($payment->load('invoice', 'customer', 'journal'), 201);
    }

    public function show(Payment $payment)
    {
        // Ensure user can only access their company's payments
        if ($payment->company_id !== auth()->user()->company_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($payment->load('invoice', 'customer', 'journal'));
    }

    public function destroy(Payment $payment)
    {
        // Ensure user can only delete their company's payments
        if ($payment->company_id !== auth()->user()->company_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check if payment has been posted
        if ($payment->journal_id) {
            return response()->json([
                'message' => 'Cannot delete posted payment. Please reverse the journal entry first.'
            ], 422);
        }

        $payment->delete();

        return response()->json(['message' => 'Payment deleted successfully']);
    }

    protected function generatePaymentNumber(int $companyId): string
    {
        $prefix = 'PAY';
        $year = now()->format('Y');
        $count = Payment::where('company_id', $companyId)
            ->whereYear('created_at', now()->year)
            ->count() + 1;

        return sprintf('%s-%s-%04d', $prefix, $year, $count);
    }
}