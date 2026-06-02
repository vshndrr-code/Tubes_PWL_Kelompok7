<?php

namespace App\Http\Controllers;

use App\Models\Budgeting;
use Illuminate\Http\Request;

class BudgetingController extends Controller
{
    public function index()
    {
        $budgetings = Budgeting::all();

        return view('budgetings.index', compact('budgetings'));
    }

    public function create()
    {
        return view('budgetings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'period' => 'required|date',
        ]);

        Budgeting::create($validated);

        return redirect()->route('budgetings.index')
            ->with('success', 'Data budgeting berhasil ditambahkan');
    }

    public function show(string $id)
    {
        $budgeting = Budgeting::findOrFail($id);

        return view('budgetings.show', compact('budgeting'));
    }

    public function edit(string $id)
    {
        $budgeting = Budgeting::findOrFail($id);

        return view('budgetings.edit', compact('budgeting'));
    }

    public function update(Request $request, string $id)
    {
        $budgeting = Budgeting::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'period' => 'required|date',
        ]);

        $budgeting->update($validated);

        return redirect()->route('budgetings.index')
            ->with('success', 'Data budgeting berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        $budgeting = Budgeting::findOrFail($id);

        $budgeting->delete();

        return redirect()->route('budgetings.index')
            ->with('success', 'Data budgeting berhasil dihapus');
    }
}