<?php

// ============================================
// File: app/Http/Controllers/Api/CustomerController.php
// ============================================

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::where('company_id', $request->user()->company_id)
            ->when($request->search, function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            })
            ->latest()
            ->paginate(20);

        return response()->json($customers);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'tax_number' => 'nullable|string|max:50',
        ]);

        $customer = Customer::create([
            'company_id' => $request->user()->company_id,
            'is_active' => true,
            ...$validated,
        ]);

        return response()->json($customer, 201);
    }

    public function show(Customer $customer)
    {
        // Ensure user can only access their company's customers
        if ($customer->company_id !== auth()->user()->company_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($customer->load('invoices', 'payments'));
    }

    public function update(Request $request, Customer $customer)
    {
        // Ensure user can only update their company's customers
        if ($customer->company_id !== auth()->user()->company_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'tax_number' => 'nullable|string|max:50',
            'is_active' => 'sometimes|boolean',
        ]);

        $customer->update($validated);

        return response()->json($customer);
    }

    public function destroy(Customer $customer)
    {
        // Ensure user can only delete their company's customers
        if ($customer->company_id !== auth()->user()->company_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check if customer has invoices
        if ($customer->invoices()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete customer with existing invoices'
            ], 422);
        }

        $customer->delete();

        return response()->json(['message' => 'Customer deleted successfully']);
    }
}

