<?php

namespace App\Models;

use App\Models\Budget;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_id',
        'category_id',
        'type',
        'amount',
        'title',
        'description',
        'transaction_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::saved(function (Transaction $transaction): void {
            $transaction->checkBudgetNotification();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'transaction_tags')->withTimestamps();
    }

    private function checkBudgetNotification(): void
    {
        if ($this->type !== 'expense') {
            return;
        }

        $budget = Budget::where('user_id', $this->user_id)
            ->where('category_id', $this->category_id)
            ->where('month', $this->transaction_date->month)
            ->where('year', $this->transaction_date->year)
            ->first();

        if (! $budget) {
            return;
        }

        $totalSpent = self::where('user_id', $this->user_id)
            ->where('category_id', $this->category_id)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $this->transaction_date->month)
            ->whereYear('transaction_date', $this->transaction_date->year)
            ->sum('amount');

        if ($totalSpent <= $budget->limit_amount) {
            return;
        }

        $this->loadMissing('category');
        $title = 'Budget terlampaui';
        $message = sprintf(
            'Pengeluaran kategori %s untuk %s telah mencapai Rp %s dan melewati batas anggaran Rp %s.',
            $this->category->name,
            $this->transaction_date->translatedFormat('F Y'),
            number_format($totalSpent, 0, ',', '.'),
            number_format($budget->limit_amount, 0, ',', '.')
        );

        if (Notification::where('user_id', $this->user_id)
            ->where('type', 'budget')
            ->where('title', $title)
            ->where('message', $message)
            ->exists()) {
            return;
        }

        Notification::create([
            'user_id' => $this->user_id,
            'title' => $title,
            'type' => 'budget',
            'message' => $message,
        ]);
    }
}
