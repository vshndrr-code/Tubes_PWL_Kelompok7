<?php

namespace App\Models;

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

    protected static function booted()
    {
        static::created(function ($transaction) {
            $account = $transaction->account;
            if ($account) {
                if ($transaction->type === 'income') {
                    $account->increment('balance', $transaction->amount);
                } else {
                    $account->decrement('balance', $transaction->amount);
                }
            }
        });

        static::updating(function ($transaction) {
            // Reverse original transaction values
            $original = $transaction->getOriginal();
            $oldAccount = Account::find($original['account_id']);
            if ($oldAccount) {
                if ($original['type'] === 'income') {
                    $oldAccount->decrement('balance', $original['amount']);
                } else {
                    $oldAccount->increment('balance', $original['amount']);
                }
            }
        });

        static::updated(function ($transaction) {
            // Apply new transaction values
            $account = $transaction->account;
            if ($account) {
                if ($transaction->type === 'income') {
                    $account->increment('balance', $transaction->amount);
                } else {
                    $account->decrement('balance', $transaction->amount);
                }
            }
        });

        static::deleted(function ($transaction) {
            $account = $transaction->account;
            if ($account) {
                if ($transaction->type === 'income') {
                    $account->decrement('balance', $transaction->amount);
                } else {
                    $account->increment('balance', $transaction->amount);
                }
            }
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
}
