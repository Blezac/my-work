<?php

// ============================================
// File: app/Http/Controllers/Api/AccountController.php
// ============================================

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $accounts = Account::where('company_id', $request->user()->company_id)
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->orderBy('code')
            ->get();

        return response()->json($accounts);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:accounts,code',
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'subtype' => 'nullable|string',
            'parent_id' => 'nullable|exists:accounts,id',
            'description' => 'nullable|string',
            'opening_balance' => 'nullable|numeric',
        ]);

        $account = Account::create([
            'company_id' => $request->user()->company_id,
            'is_system' => false,
            'is_active' => true,
            'opening_balance' => $validated['opening_balance'] ?? 0,
            ...$validated,
        ]);

        return response()->json($account, 201);
    }

    public function show(Account $account)
    {
        // Ensure user can only access their company's accounts
        if ($account->company_id !== auth()->user()->company_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($account->load('parent', 'children'));
    }

    public function update(Request $request, Account $account)
    {
        // Ensure user can only update their company's accounts
        if ($account->company_id !== auth()->user()->company_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Don't allow editing system accounts
        if ($account->is_system) {
            return response()->json(['message' => 'Cannot edit system accounts'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $account->update($validated);

        return response()->json($account);
    }

    public function destroy(Account $account)
    {
        // Ensure user can only delete their company's accounts
        if ($account->company_id !== auth()->user()->company_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Don't allow deleting system accounts
        if ($account->is_system) {
            return response()->json(['message' => 'Cannot delete system accounts'], 403);
        }

        // Check if account has transactions
        if ($account->journalLines()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete account with existing transactions'
            ], 422);
        }

        $account->delete();

        return response()->json(['message' => 'Account deleted successfully']);
    }

    public function byType(Request $request)
    {
        $request->validate([
            'type' => 'required|in:asset,liability,equity,revenue,expense',
        ]);

        $accounts = Account::where('company_id', $request->user()->company_id)
            ->where('type', $request->type)
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        return response()->json($accounts);
    }
}