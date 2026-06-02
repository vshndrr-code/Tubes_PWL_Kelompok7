<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RecurringTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_id',
        'category_id',
        'type',
        'title',
        'description',
        'amount',
        'frequency',
        'interval',
        'start_date',
        'end_date',
        'next_occurrence_date',
        'active',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'next_occurrence_date' => 'date',
        'active' => 'boolean',
    ];

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
        return $this->belongsToMany(Tag::class, 'recurring_transaction_tag')->withTimestamps();
    }

    public function scheduleNextOccurrence(): void
    {
        if (! $this->next_occurrence_date) {
            return;
        }

        $nextDate = $this->next_occurrence_date->copy();

        do {
            $nextDate = $this->incrementDate($nextDate);
        } while ($nextDate->lte(now()->startOfDay()) && $this->shouldContinue($nextDate));

        if ($this->end_date && $nextDate->gt($this->end_date)) {
            $this->active = false;
            $this->next_occurrence_date = null;
        } else {
            $this->next_occurrence_date = $nextDate;
        }

        $this->save();
    }

    public function isDue(): bool
    {
        return $this->active && $this->next_occurrence_date && $this->next_occurrence_date->lte(now()->startOfDay());
    }

    private function incrementDate(Carbon $date): Carbon
    {
        return match ($this->frequency) {
            'daily' => $date->addDays($this->interval),
            'weekly' => $date->addWeeks($this->interval),
            'yearly' => $date->addYears($this->interval),
            default => $date->addMonths($this->interval),
        };
    }

    private function shouldContinue(Carbon $date): bool
    {
        if (! $this->end_date) {
            return true;
        }

        return $date->lte($this->end_date);
    }
}
