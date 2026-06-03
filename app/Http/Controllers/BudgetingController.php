<?php

namespace App\Http\Controllers;

use App\Models\Budgeting;
use App\Models\Category;
use Illuminate\Http\Request;

class BudgetingController extends Controller
{
    public function index()
    {
        $budgetings = Budgeting::with('category')->get();
        $categories = Category::orderBy('name')->get();
        return view('budgetings.index', compact('budgetings', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('budgetings.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'limit_amount' => 'required|numeric',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer',
        ]);

        Budgeting::create($validated);
        return redirect()
            ->route('budgetings.index')
            ->with('success', 'Data budgeting berhasil ditambahkan');
    }

    public function show(string $id)
    {
        $budgeting = Budgeting::with('category')->findOrFail($id);
        return view('budgetings.show', compact('budgeting'));
    }

    public function edit(string $id)
    {
        $budgeting = Budgeting::findOrFail($id);
        $categories = Category::orderBy('name')->get();
        return view('budgetings.edit', compact('budgeting', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $budgeting = Budgeting::findOrFail($id);
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'limit_amount' => 'required|numeric',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer',
        ]);

        $budgeting->update($validated);
        return redirect()
            ->route('budgetings.index')
            ->with('success', 'Data budgeting berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        $budgeting = Budgeting::findOrFail($id);
        $budgeting->delete();
        return redirect()
            ->route('budgetings.index')
            ->with('success', 'Data budgeting berhasil dihapus');
    }
}