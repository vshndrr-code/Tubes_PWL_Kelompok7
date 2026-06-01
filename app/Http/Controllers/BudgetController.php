<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index()
    {
        $budgets = Budget::with('category')
            ->where('user_id', auth()->id())
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('budgets.index', compact('budgets'));
    }

    public function create()
    {
        $categories = Category::where('user_id', auth()->id())
            ->where('type', 'expense')
            ->orderBy('name')
            ->get();

        return view('budgets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'limit_amount' => 'required|numeric|min:0.01',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000',
        ]);

        $category = Category::where('id', $data['category_id'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        Budget::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'category_id' => $category->id,
                'month' => $data['month'],
                'year' => $data['year'],
            ],
            ['limit_amount' => $data['limit_amount']]
        );

        return redirect()->route('budgets.index')->with('success', 'Budget berhasil disimpan.');
    }

    public function edit(Budget $budget)
    {
        $this->authorizeOwner($budget);

        $categories = Category::where('user_id', auth()->id())
            ->where('type', 'expense')
            ->orderBy('name')
            ->get();

        return view('budgets.edit', compact('budget', 'categories'));
    }

    public function update(Request $request, Budget $budget)
    {
        $this->authorizeOwner($budget);

        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'limit_amount' => 'required|numeric|min:0.01',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000',
        ]);

        $category = Category::where('id', $data['category_id'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $budget->update([
            'category_id' => $category->id,
            'limit_amount' => $data['limit_amount'],
            'month' => $data['month'],
            'year' => $data['year'],
        ]);

        return redirect()->route('budgets.index')->with('success', 'Budget berhasil diperbarui.');
    }

    public function destroy(Budget $budget)
    {
        $this->authorizeOwner($budget);

        $budget->delete();

        return redirect()->route('budgets.index')->with('success', 'Budget berhasil dihapus.');
    }

    private function authorizeOwner(Budget $budget): void
    {
        if ($budget->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
