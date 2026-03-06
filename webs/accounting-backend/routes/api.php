<?php

// routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ReportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes (no authentication required)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    
    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/charts', [DashboardController::class, 'charts']);

    // Chart of Accounts
    Route::apiResource('accounts', AccountController::class);
    Route::get('/accounts-by-type', [AccountController::class, 'byType']);

    // Customers
    Route::apiResource('customers', CustomerController::class);

    // Invoices
    Route::apiResource('invoices', InvoiceController::class);
    Route::post('/invoices/{invoice}/post', [InvoiceController::class, 'post']);
    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'pdf']);

    // Expenses
    Route::apiResource('expenses', ExpenseController::class);
    Route::post('/expenses/{expense}/post', [ExpenseController::class, 'post']);
    Route::post('/expenses/upload-receipt', [ExpenseController::class, 'uploadReceipt']);

    // Payments
    Route::apiResource('payments', PaymentController::class);
    Route::post('/payments/{payment}/post', [PaymentController::class, 'post']);

    // Reports
    Route::get('/reports/trial-balance', [ReportController::class, 'trialBalance']);
    Route::get('/reports/profit-loss', [ReportController::class, 'profitLoss']);
    Route::get('/reports/balance-sheet', [ReportController::class, 'balanceSheet']);
    Route::get('/reports/cash-flow', [ReportController::class, 'cashFlow']);

     // Delete routes
    Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy']);
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy']);
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy']);
    Route::delete('/accounts/{account}', [AccountController::class, 'destroy']);
});