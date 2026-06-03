<?php

namespace App\Http\Controllers;

use App\Models\Budgeting;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // 👈 Impor Auth Facade di sini

class BudgetingController extends Controller
{
    public function index()
    {
        $budgetings = Budgeting::where('user_id', Auth::id()) // 👈 Menggunakan Auth::id()
            ->with('category')
            ->paginate(15);
            
        $categories = Category::where(function($query) {
                $query->where('user_id', Auth::id()) // 👈 Menggunakan Auth::id()
                      ->orWhereNull('user_id');
            })
            ->orderBy('name')
            ->get();
            
        return view('budgetings.index', compact('budgetings', 'categories'));
    }

    public function create()
    {
        $categories = Category::where(function($query) {
                $query->where('user_id', Auth::id()) // 👈 Menggunakan Auth::id()
                      ->orWhereNull('user_id');
            })
            ->orderBy('name')
            ->get();
        return view('budgetings.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id', 'integer'],
            'limit_amount' => ['required', 'numeric', 'min:0.01'],
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'year' => ['required', 'integer', 'min:2020', 'max:2100'],
        ], [
            'category_id.required' => 'Pilih kategori terlebih dahulu.',
            'category_id.exists' => 'Kategori yang dipilih tidak ditemukan.',
            'limit_amount.required' => 'Masukkan jumlah limit budget.',
            'limit_amount.min' => 'Limit budget minimal Rp 0.01.',
            'month.required' => 'Pilih bulan terlebih dahulu.',
            'year.required' => 'Masukkan tahun.',
        ]);

        Budgeting::create(array_merge($validated, ['user_id' => Auth::id()])); // 👈 Menggunakan Auth::id()
        return redirect()
            ->route('budgetings.index')
            ->with('success', 'Budget berhasil ditambahkan.');
    }

    public function show(Budgeting $budgeting)
    {
        $this->authorize('view', $budgeting);
        return view('budgetings.show', compact('budgeting'));
    }

    public function edit(Budgeting $budgeting)
    {
        $this->authorize('update', $budgeting);
        $categories = Category::where('user_id', Auth::id()) // 👈 Menggunakan Auth::id()
            ->orderBy('name')
            ->get();
        return view('budgetings.edit', compact('budgeting', 'categories'));
    }

    public function update(Request $request, Budgeting $budgeting)
    {
        $this->authorize('update', $budgeting);

        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id', 'integer'],
            'limit_amount' => ['required', 'numeric', 'min:0.01'],
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'year' => ['required', 'integer', 'min:2020', 'max:2100'],
        ], [
            'category_id.required' => 'Pilih kategori terlebih dahulu.',
            'category_id.exists' => 'Kategori yang dipilih tidak ditemukan.',
            'limit_amount.required' => 'Masukkan jumlah limit budget.',
            'limit_amount.min' => 'Limit budget minimal Rp 0.01.',
            'month.required' => 'Pilih bulan terlebih dahulu.',
            'year.required' => 'Masukkan tahun.',
        ]);

        $budgeting->update($validated);
        return redirect()
            ->route('budgetings.index')
            ->with('success', 'Budget berhasil diperbarui.');
    }

    public function destroy(Budgeting $budgeting)
    {
        $this->authorize('delete', $budgeting);
        $budgeting->delete();
        return redirect()
            ->route('budgetings.index')
            ->with('success', 'Budget berhasil dihapus.');
    }
}