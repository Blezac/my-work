<?php
// ============================================
// File: app/Models/Account.php
// ============================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id', 
        'code', 
        'name', 
        'type', 
        'subtype',
        'parent_id', 
        'description', 
        'is_system', 
        'is_active', 
        'opening_balance'
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'is_active' => 'boolean',
        'opening_balance' => 'decimal:2',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    public function journalLines(): HasMany
    {
        return $this->hasMany(JournalLine::class);
    }

    // Calculate current balance
    public function getBalanceAttribute(): float
    {
        $lines = $this->journalLines()
            ->whereHas('journal', fn($q) => $q->where('is_posted', true))
            ->get();

        $debitTotal = $lines->sum('debit');
        $creditTotal = $lines->sum('credit');

        // Asset, Expense: Debit increases, Credit decreases
        if (in_array($this->type, ['asset', 'expense'])) {
            return $this->opening_balance + $debitTotal - $creditTotal;
        }

        // Liability, Equity, Revenue: Credit increases, Debit decreases
        return $this->opening_balance + $creditTotal - $debitTotal;
    }
}

