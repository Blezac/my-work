<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// database/migrations/2024_01_01_000009_create_expenses_table.php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('expense_account_id')->constrained('accounts');
            $table->foreignId('payment_account_id')->constrained('accounts');
            $table->string('expense_number')->unique();
            $table->date('expense_date');
            $table->string('vendor_name')->nullable();
            $table->decimal('amount', 15, 2);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total', 15, 2);
            $table->text('description')->nullable();
            $table->string('receipt_path')->nullable();
            $table->foreignId('journal_id')->nullable()->constrained();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['company_id', 'expense_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
