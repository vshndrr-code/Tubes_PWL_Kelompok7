<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Category;

class Budgeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'limit_amount',
        'month',
        'year',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

         public function getSpentAmountAttribute()
    {
        return \App\Models\Transaction::where('user_id', $this->user_id)
            ->where('category_id', $this->category_id)
            ->whereMonth('transaction_date', $this->month) // Diubah ke 'transaction_date'
            ->whereYear('transaction_date', $this->year)   // Diubah ke 'transaction_date'
            ->sum('amount');
    }
}