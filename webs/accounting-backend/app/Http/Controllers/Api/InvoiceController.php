<?php
// app/Http/Controllers/Api/InvoiceController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Services\AccountingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function __construct(
        protected AccountingService $accountingService
    ) {}

    public function index(Request $request)
    {
        $invoices = Invoice::where('company_id', $request->user()->company_id)
            ->with('customer')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest('invoice_date')
            ->paginate(20);

        return response()->json($invoices);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after:invoice_date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $subtotal = 0;
            $taxAmount = 0;

            foreach ($validated['items'] as $item) {
                $itemTotal = $item['quantity'] * $item['unit_price'];
                $itemTax = $itemTotal * (($item['tax_rate'] ?? 0) / 100);
                
                $subtotal += $itemTotal;
                $taxAmount += $itemTax;
            }

            $total = $subtotal + $taxAmount;

            $invoice = Invoice::create([
                'company_id' => $request->user()->company_id,
                'customer_id' => $validated['customer_id'],
                'invoice_number' => $this->generateInvoiceNumber($request->user()->company_id),
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'],
                'status' => 'draft',
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total' => $total,
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                $itemTotal = $item['quantity'] * $item['unit_price'];
                $itemTax = $itemTotal * (($item['tax_rate'] ?? 0) / 100);

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'tax_rate' => $item['tax_rate'] ?? 0,
                    'tax_amount' => $itemTax,
                    'total' => $itemTotal + $itemTax,
                ]);
            }

            return response()->json($invoice->load('items', 'customer'), 201);
        });
    }

    public function show(Invoice $invoice)
    {
        return response()->json($invoice->load('items', 'customer', 'payments'));
    }

    public function post(Invoice $invoice)
    {
        if ($invoice->status !== 'draft') {
            return response()->json(['message' => 'Only draft invoices can be posted'], 400);
        }

        $journal = $this->accountingService->postInvoice($invoice);

        $invoice->update(['status' => 'sent']);

        return response()->json([
            'invoice' => $invoice->load('journal'),
            'message' => 'Invoice posted successfully',
        ]);
    }

protected function generateInvoiceNumber(int $companyId): string
{
    $prefix = 'INV';
    $year = now()->format('Y');
    $count = Invoice::where('company_id', $companyId)
        ->whereYear('created_at', now()->year)
        ->count() + 1;

    return sprintf('%s-%s-%04d', $prefix, $year, $count);
}

public function destroy(Invoice $invoice)
{
    // Check authorization
    if ($invoice->company_id !== auth()->user()->company_id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }
    
    // Check if invoice has payments
    if ($invoice->payments()->count() > 0) {
        return response()->json([
            'message' => 'Cannot delete invoice with associated payments. Delete payments first.'
        ], 422);
    }
    
    $invoice->delete();
    
    return response()->json(['message' => 'Invoice deleted successfully']);
}
}