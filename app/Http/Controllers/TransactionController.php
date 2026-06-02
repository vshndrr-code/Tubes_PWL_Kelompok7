<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('user_id', auth()->id())
            ->with(['account', 'category', 'tags'])
            ->orderBy('transaction_date', 'desc')
            ->paginate(15);

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $accounts = Account::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        $categories = Category::where('user_id', auth()->id())
            ->orWhereNull('user_id')
            ->orderBy('name')
            ->get();

        $tags = auth()->user()->tags ?? collect();

        return view('transactions.create', compact('accounts', 'categories', 'tags'));
    }

    public function store(StoreTransactionRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();

        $transaction = DB::transaction(function () use ($validated, $request) {
            $transaction = Transaction::create($validated);

            // Update saldo akun sesuai tipe transaksi
            $account = Account::findOrFail($validated['account_id']);
            if ($validated['type'] === 'income') {
                $account->balance += $validated['amount'];
            } else {
                $account->balance -= $validated['amount'];
            }
            $account->save();

            if ($request->has('tags') && is_array($request->tags)) {
                $transaction->tags()->sync($request->tags);
            }

            return $transaction;
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

        $categories = Category::where('user_id', auth()->id())
            ->orWhereNull('user_id')
            ->orderBy('name')
            ->get();

        $tags = auth()->user()->tags ?? collect();

        return view('transactions.edit', compact('transaction', 'accounts', 'categories', 'tags'));
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request, $transaction) {
            // 1. Reverse efek transaksi lama pada akun lama
            $oldAccount = Account::findOrFail($transaction->account_id);
            if ($transaction->type === 'income') {
                $oldAccount->balance -= $transaction->amount;
            } else {
                $oldAccount->balance += $transaction->amount;
            }
            $oldAccount->save();

            // 2. Update data transaksi
            $transaction->update($validated);

            // 3. Apply efek transaksi baru pada akun baru (bisa akun yang sama atau berbeda)
            $newAccount = Account::findOrFail($validated['account_id']);
            if ($validated['type'] === 'income') {
                $newAccount->balance += $validated['amount'];
            } else {
                $newAccount->balance -= $validated['amount'];
            }
            $newAccount->save();

            if ($request->has('tags') && is_array($request->tags)) {
                $transaction->tags()->sync($request->tags);
            }
        });

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        DB::transaction(function () use ($transaction) {
            // Reverse efek transaksi pada saldo akun
            $account = Account::findOrFail($transaction->account_id);
            if ($transaction->type === 'income') {
                $account->balance -= $transaction->amount;
            } else {
                $account->balance += $transaction->amount;
            }
            $account->save();

            $transaction->delete();
        });

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus.');
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
}
