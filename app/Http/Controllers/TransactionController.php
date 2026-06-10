<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use App\Models\RecurringTransaction;
use App\Models\Transaction;
use App\Http\Requests\StoreRecurringTransactionRequest;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        // Process due recurring transactions
        $this->processDueRecurringTransactions();

        $transactions = Transaction::where('user_id', auth()->id())
            ->with(['account', 'category', 'tags', 'savingsGoal'])
            ->orderBy('transaction_date', 'desc')
            ->paginate(15);

        $recurringTransactions = RecurringTransaction::where('user_id', auth()->id())
            ->where('active', true)
            ->with(['account', 'category'])
            ->get();

        $accountBalance = Account::where('user_id', auth()->id())->sum('balance');

        return view('transactions.index', compact('transactions', 'recurringTransactions', 'accountBalance'));
    }

    public function create()
    {
        $userId = auth()->user()->id;

        $accounts = Account::where('user_id', $userId)
            ->orderBy('name')
            ->get();

        if ($accounts->isEmpty()) {
            return redirect()->route('accounts.index')
                ->with('accounts_required', true)
                ->with('accounts_required_cta_url', route('accounts.create'))
                ->with('error', 'Kamu belum mempunyai akun. Silakan tambahkan akun terlebih dahulu sebelum membuat transaksi.');
        }

        $categories = Category::where(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->orWhereNull('user_id');
        })
            ->distinct('name')
            ->orderByRaw("(name = 'Lain-lain') ASC")
            ->orderBy('name')
            ->get();

        $tags = auth()->user()->tags ?? collect();

        $budgets = \App\Models\Budgeting::where('user_id', $userId)
            ->orderBy('name')
            ->get();

        $savingsGoals = \App\Models\SavingsGoals::where('user_id', $userId)
            ->orderBy('name')
            ->get();

        return view('transactions.create', compact('accounts', 'categories', 'tags', 'budgets', 'savingsGoals'));
    }

    public function createRecurring()
    {
        $userId = auth()->user()->id;

        $accounts = Account::where('user_id', $userId)
            ->orderBy('name')
            ->get();

        return view('transactions.create-recurring', compact('accounts'));
    }

    public function storeRecurring(StoreRecurringTransactionRequest $request)
    {
        $validated = $request->validated();
        $startDate = \Carbon\Carbon::parse($validated['start_date'])->startOfDay();

        $category = Category::where(function($query) {
            $query->where('user_id', auth()->id())
                  ->orWhereNull('user_id');
            })
            ->distinct('name')
            ->orderByRaw("(name = 'Lain-lain') ASC")
            ->orderBy('name')
            ->first();

        DB::transaction(function () use ($validated, $category, $startDate, &$recurringTransaction) {
            $recurringTransaction = RecurringTransaction::create([
                'user_id' => auth()->id(),
                'account_id' => $validated['account_id'],
                'category_id' => $category ? $category->id : null,
                'type' => 'expense',
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'amount' => $validated['amount'],
                'frequency' => $validated['recurring_frequency'],
                'interval' => 1,
                'start_date' => $startDate,
                'end_date' => null,
                'next_occurrence_date' => $startDate,
                'active' => true,
            ]);
        });

        return redirect()->route('transactions.index')
            ->with('success', 'Recurring transaction berhasil dibuat. Saldo akan otomatis berkurang mulai tanggal ' . $startDate->format('d M Y') . '.');
    }

    public function showRecurring(RecurringTransaction $recurringTransaction)
    {
        if ($recurringTransaction->user_id !== auth()->id()) {
            abort(403);
        }

        return view('transactions.show-recurring', compact('recurringTransaction'));
    }

    public function editRecurring(RecurringTransaction $recurringTransaction)
    {
        if ($recurringTransaction->user_id !== auth()->id()) {
            abort(403);
        }

        $userId = auth()->user()->id;

        $accounts = Account::where('user_id', $userId)
            ->orderBy('name')
            ->get();

        return view('transactions.edit-recurring', compact('recurringTransaction', 'accounts'));
    }

    public function updateRecurring(StoreRecurringTransactionRequest $request, RecurringTransaction $recurringTransaction)
    {
        if ($recurringTransaction->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validated();
        $startDate = \Carbon\Carbon::parse($validated['start_date'])->startOfDay();

        $recurringTransaction->update([
            'account_id' => $validated['account_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'amount' => $validated['amount'],
            'frequency' => $validated['recurring_frequency'],
            'start_date' => $startDate,
        ]);

        return redirect()->route('transactions.index')
            ->with('success', 'Recurring transaction berhasil diperbarui.');
    }

    public function destroyRecurring(RecurringTransaction $recurringTransaction)
    {
        if ($recurringTransaction->user_id !== auth()->id()) {
            abort(403);
        }

        $title = $recurringTransaction->title;
        $recurringTransaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Recurring transaction "' . $title . '" berhasil dihapus.');
    }

    private function createRecurringTransactionOccurrence(RecurringTransaction $recurringTransaction): void
    {
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

        $account = $transaction->account;
        if ($transaction->type === 'income') {
            $account->increment('balance', $transaction->amount);
        } else {
            $account->decrement('balance', $transaction->amount);
        }
    }

    public function store(StoreTransactionRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();

        // Enforce: budget hanya untuk expense, saving goals hanya untuk income
        if ($validated['type'] === 'income') {
            $validated['budgeting_id'] = null;
        }
        if ($validated['type'] === 'expense') {
            $validated['savings_goal_id'] = null;
        }

        DB::transaction(function () use ($validated, $request, &$transaction) {
            $transaction = Transaction::create($validated);
            $this->applyTransactionBalance($transaction);

            // Sync tags (empty array clears all tags if none selected)
            $transaction->tags()->sync($request->input('tags', []));
        });

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $accounts = Account::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        $categories = Category::where(function($query) {
            $query->where('user_id', auth()->id())
                  ->orWhereNull('user_id');
            })
            ->distinct('name')
            ->orderByRaw("(name = 'Lain-lain') ASC")
            ->orderBy('name')
            ->get();

        $tags = auth()->user()->tags ?? collect();

        $budgets = \App\Models\Budgeting::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        $savingsGoals = \App\Models\SavingsGoals::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        return view('transactions.edit', compact('transaction', 'accounts', 'categories', 'tags', 'budgets', 'savingsGoals'));
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $validated = $request->validated();

        // Enforce: budget hanya untuk expense, saving goals hanya untuk income
        if ($validated['type'] === 'income') {
            $validated['budgeting_id'] = null;
        }
        if ($validated['type'] === 'expense') {
            $validated['savings_goal_id'] = null;
        }

        DB::transaction(function () use ($validated, $request, $transaction) {
            $this->reverseTransactionBalance($transaction);

            $transaction->update($validated);
            $this->applyTransactionBalance($transaction);

            // Sync tags (empty array clears all tags if none selected)
            $transaction->tags()->sync($request->input('tags', []));
        });

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        DB::transaction(function () use ($transaction) {
            $this->reverseTransactionBalance($transaction);
            $transaction->delete();
        });

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus.');
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

    private function reverseTransactionBalance(Transaction $transaction): void
    {
        $account = $transaction->account;

        if ($transaction->type === 'income') {
            $account->decrement('balance', $transaction->amount);
        } else {
            $account->increment('balance', $transaction->amount);
        }
    }

    public function getByAccount(Account $account)
    {
        $this->authorize('view', $account);

        $transactions = Transaction::where('account_id', $account->id)
            ->with(['category', 'tags'])
            ->orderBy('transaction_date', 'desc')
            ->get();

        return response()->json($transactions);
    }

    public function getByCategory(Category $category)
    {
        $this->authorize('view', $category);

        $transactions = Transaction::where('category_id', $category->id)
            ->where('user_id', auth()->id())
            ->with(['account', 'tags'])
            ->orderBy('transaction_date', 'desc')
            ->get();

        return response()->json($transactions);
    }

    private function processDueRecurringTransactions(): void
    {
        $recurringTransactions = RecurringTransaction::with('tags')
            ->where('user_id', auth()->id())
            ->where('active', true)
            ->whereDate('next_occurrence_date', '<=', now()->startOfDay())
            ->get();

        foreach ($recurringTransactions as $recurringTransaction) {
            while ($recurringTransaction->isDue()) {
                DB::transaction(function () use ($recurringTransaction) {
                    $this->createRecurringTransactionOccurrence($recurringTransaction);
                    $recurringTransaction->scheduleNextOccurrence();
                });
            }
        }
    }
}
