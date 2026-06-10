<?php

namespace App\Http\Controllers;

use App\Models\SavingsGoals;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavingsGoalsController extends Controller
{
    public function index()
    {
        $savingsGoals = SavingsGoals::where('user_id', Auth::id())
                                    ->with(['account', 'transactions'])
                                    ->get();

        return view('savings_goals.index', compact('savingsGoals'));
    }

    public function create()
    {
        $accounts = Account::where('user_id', Auth::id())
                           ->where('archived_at', null)
                           ->get();

        return view('savings_goals.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:0',
            'current_amount' => 'nullable|numeric|min:0',
            'deadline' => 'nullable|date|after:today',
            'account_id' => 'nullable|exists:accounts,id',
        ]);

        // Verify account belongs to user
        if ($validated['account_id']) {
            $account = Account::where('user_id', Auth::id())
                             ->findOrFail($validated['account_id']);
        }

        SavingsGoals::create([
            'user_id' => Auth::id(),
            ...$validated,
        ]);

        return redirect()->route('savings-goals.index')->with('status', 'Savings goal created successfully!');
    }

    public function show(string $id)
    {
        $savingsGoal = SavingsGoals::where('user_id', Auth::id())
                                   ->with(['account', 'transactions' => function ($q) {
                                       $q->with(['account', 'category'])->orderBy('transaction_date', 'desc');
                                   }])
                                   ->findOrFail($id);

        return view('savings_goals.show', compact('savingsGoal'));
    }

    public function edit(string $id)
    {
        $savingsGoal = SavingsGoals::where('user_id', Auth::id())->findOrFail($id);

        $accounts = Account::where('user_id', Auth::id())
                           ->where('archived_at', null)
                           ->get();

        return view('savings_goals.edit', compact('savingsGoal', 'accounts'));
    }

    public function update(Request $request, string $id)
    {
        $savingsGoal = SavingsGoals::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:0',
            'current_amount' => 'nullable|numeric|min:0',
            'deadline' => 'nullable|date|after:today',
            'account_id' => 'nullable|exists:accounts,id',
        ]);

        // Verify account belongs to user
        if ($validated['account_id']) {
            $account = Account::where('user_id', Auth::id())
                             ->findOrFail($validated['account_id']);
        }

        $savingsGoal->update($validated);

        return redirect()->route('savings-goals.index')->with('status', 'Savings goal updated successfully!');
    }

    public function destroy(string $id)
    {
        $savingsGoal = SavingsGoals::where('user_id', Auth::id())->findOrFail($id);

        $savingsGoal->delete();

        return redirect()->route('savings-goals.index')->with('status', 'Savings goal deleted successfully!');
    }
}