<?php

namespace App\Console\Commands;

use App\Models\RecurringTransaction;
use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessRecurringTransactions extends Command
{
    protected $signature = 'recurring:process';
    protected $description = 'Create due transaction entries for recurring transaction definitions.';

    public function handle(): int
    {
        $recurringTransactions = RecurringTransaction::with('tags')
            ->where('active', true)
            ->whereDate('next_occurrence_date', '<=', now()->startOfDay())
            ->get();

        foreach ($recurringTransactions as $recurringTransaction) {
            while ($recurringTransaction->isDue()) {
                DB::transaction(function () use ($recurringTransaction) {
                    $transaction = Transaction::create([
                        'user_id' => $recurringTransaction->user_id,
                        'account_id' => $recurringTransaction->account_id,
                        'category_id' => $recurringTransaction->category_id,
                        'type' => $recurringTransaction->type,
                        'title' => $recurringTransaction->title,
                        'description' => $recurringTransaction->description,
                        'amount' => $recurringTransaction->amount,
                        'transaction_date' => $recurringTransaction->next_occurrence_date,
                    ]);

                    if ($recurringTransaction->tags->isNotEmpty()) {
                        $transaction->tags()->sync($recurringTransaction->tags->pluck('id')->toArray());
                    }

                    $this->applyTransactionBalance($transaction);
                    $recurringTransaction->scheduleNextOccurrence();
                });
            }
        }

        return 0;
    }

    private function applyTransactionBalance(Transaction $transaction): void
    {
        $account = $transaction->account;

        if ($transaction->type === 'income') {
            $account->increment('balance', $transaction->amount);
        } else {
            $account->decrement('balance', $transaction->amount);
        }
    }
}
