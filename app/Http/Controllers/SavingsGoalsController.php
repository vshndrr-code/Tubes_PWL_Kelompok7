<?php

namespace App\Http\Controllers;

use App\Models\SavingsGoals;
use Illuminate\Http\Request;

class SavingsGoalsController extends Controller
{
    public function index()
    {
        $savingsGoals = SavingsGoals::all();

        return view('savings_goals.index', compact('savingsGoals'));
    }

    public function create()
    {
        return view('savings_goals.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric',
            'current_amount' => 'nullable|numeric',
            'deadline' => 'nullable|date',
        ]);

        SavingsGoals::create($validated);

        return redirect()->route('savings-goals.index');
    }

    public function show(string $id)
    {
        $savingsGoal = SavingsGoals::findOrFail($id);

        return view('savings_goals.show', compact('savingsGoal'));
    }

    public function edit(string $id)
    {
        $savingsGoal = SavingsGoals::findOrFail($id);

        return view('savings_goals.edit', compact('savingsGoal'));
    }

    public function update(Request $request, string $id)
    {
        $savingsGoal = SavingsGoals::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric',
            'current_amount' => 'nullable|numeric',
            'deadline' => 'nullable|date',
        ]);

        $savingsGoal->update($validated);

        return redirect()->route('savings-goals.index');
    }

    public function destroy(string $id)
    {
        $savingsGoal = SavingsGoals::findOrFail($id);

        $savingsGoal->delete();

        return redirect()->route('savings-goals.index');
    }
}