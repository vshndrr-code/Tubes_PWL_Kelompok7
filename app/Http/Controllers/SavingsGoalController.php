<?php

namespace App\Http\Controllers;

use App\Models\SavingsGoal;
use Illuminate\Http\Request;

class SavingsGoalController extends Controller
{
    public function index()
    {
        $goals = SavingsGoal::where('user_id', auth()->id())
            ->orderBy('deadline')
            ->get();

        return view('savings_goals.index', compact('goals'));
    }

    public function create()
    {
        return view('savings_goals.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'target_amount' => 'required|numeric|min:0.01',
            'current_amount' => 'nullable|numeric|min:0',
            'deadline' => 'required|date',
            'status' => 'required|in:active,completed,paused',
        ]);

        SavingsGoal::create(array_merge($data, ['user_id' => auth()->id(), 'current_amount' => $data['current_amount'] ?? 0]));

        return redirect()->route('savings-goals.index')->with('success', 'Goal tabungan berhasil dibuat.');
    }

    public function edit(SavingsGoal $savings_goal)
    {
        $this->authorizeOwner($savings_goal);

        return view('savings_goals.edit', ['goal' => $savings_goal]);
    }

    public function update(Request $request, SavingsGoal $savings_goal)
    {
        $this->authorizeOwner($savings_goal);

        $data = $request->validate([
            'name' => 'required|string|max:150',
            'target_amount' => 'required|numeric|min:0.01',
            'current_amount' => 'nullable|numeric|min:0',
            'deadline' => 'required|date',
            'status' => 'required|in:active,completed,paused',
        ]);

        $savings_goal->update(array_merge($data, ['current_amount' => $data['current_amount'] ?? 0]));

        return redirect()->route('savings-goals.index')->with('success', 'Goal tabungan berhasil diperbarui.');
    }

    public function destroy(SavingsGoal $savings_goal)
    {
        $this->authorizeOwner($savings_goal);

        $savings_goal->delete();

        return redirect()->route('savings-goals.index')->with('success', 'Goal tabungan berhasil dihapus.');
    }

    private function authorizeOwner(SavingsGoal $savings_goal): void
    {
        if ($savings_goal->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
