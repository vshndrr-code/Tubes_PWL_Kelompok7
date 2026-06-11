<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\SavingsGoals;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Budgeting;
use Illuminate\Http\Request;

class AuditorController extends Controller
{
    /**
     * Tampilkan Dashboard Auditor dengan data statistik.
     */
    public function dashboard()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalTransactions = Transaction::count();
        $globalCategoriesCount = Category::whereNull('user_id')->count();

        // Savings Goals breakdown for donut chart
        $savingsAchieved  = SavingsGoals::where(function ($q) {
            $q->where('status', 'completed')
              ->orWhereColumn('current_amount', '>=', 'target_amount');
        })->count();

        $savingsEmpty = SavingsGoals::where('current_amount', '<=', 0)
            ->where(function ($q) {
                $q->where('status', '!=', 'completed')
                  ->whereColumn('current_amount', '<', 'target_amount');
            })->count();

        $savingsInProgress = SavingsGoals::where('current_amount', '>', 0)
            ->where(function ($q) {
                $q->where('status', '!=', 'completed')
                  ->whereColumn('current_amount', '<', 'target_amount');
            })->count();

        $totalAchievedSavings = $savingsAchieved;
        $totalSavingsGoals = $savingsAchieved + $savingsEmpty + $savingsInProgress;

        // Budgeting breakdown for donut chart
        // Hijau: belum habis (spent > 0 && spent < limit)
        // Merah: sudah habis (spent >= limit)
        // Abu-abu: belum dipake sama sekali (spent == 0)
        $budgets = Budgeting::with('transactions')->get();
        $budgetsGreen = 0;
        $budgetsRed = 0;
        $budgetsGray = 0;

        foreach ($budgets as $budget) {
            $spent = (float) $budget->spent_amount;
            $limit = (float) $budget->limit_amount;
            if ($spent == 0.0) {
                $budgetsGray++;
            } elseif ($spent >= $limit) {
                $budgetsRed++;
            } else {
                $budgetsGreen++;
            }
        }
        $totalBudgets = $budgets->count();

        return response()
            ->view('auditor.dashboard', compact(
                'totalUsers',
                'totalTransactions',
                'totalAchievedSavings',
                'globalCategoriesCount',
                'savingsAchieved',
                'savingsInProgress',
                'savingsEmpty',
                'totalSavingsGoals',
                'budgetsGreen',
                'budgetsRed',
                'budgetsGray',
                'totalBudgets'
            ))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Logout khusus auditor via GET (dipanggil oleh JS saat back navigation).
     */
    public function logout(\Illuminate\Http\Request $request)
    {
        \Illuminate\Support\Facades\Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auditor.login');
    }

    /**
     * Tampilkan Halaman Moderasi Kategori Global (SDGs).
     */
    public function categories()
    {
        // Ambil semua kategori global (user_id IS NULL)
        $globalCategories = Category::whereNull('user_id')
            ->orderBy('name')
            ->get();

        return view('auditor.categories', compact('globalCategories'));
    }

    /**
     * Tampilkan Form Pembuatan Kategori Global Baru.
     */
    public function createCategory()
    {
        return view('auditor.create_category');
    }

    /**
     * Menyimpan kategori global baru (SDGs) dengan user_id = null.
     */
    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,NULL,id,user_id,NULL',
            'type' => 'required|in:expense,income',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ], [
            'name.unique' => 'Nama kategori global ini sudah ada.',
            'color.regex' => 'Format warna HEX tidak valid (harus diawali dengan # diikuti 6 digit heksadesimal).',
        ]);

        Category::create(array_merge($data, ['user_id' => null]));

        return redirect()->route('auditor.categories.index')->with('success', 'Kategori global (SDGs) berhasil ditambahkan.');
    }

    /**
     * Menghapus kategori global dari database.
     */
    public function destroyCategory(Category $category)
    {
        $name = $category->name;
        $category->delete();

        return redirect()->route('auditor.categories.index')
            ->with('success', 'Kategori global (SDGs) "' . $name . '" berhasil dihapus.');
    }

    /**
     * Halaman moderasi seluruh tag sistem.
     */
    public function tags()
    {
        $tags = Tag::with('user')
            ->withCount('transactions')
            ->orderBy('name')
            ->get();

        return view('auditor.tags', compact('tags'));
    }

    /**
     * Menghapus tag dari database (moderasi).
     */
    public function destroyTag(Tag $tag)
    {
        $name = $tag->name;
        $tag->delete();

        return redirect()->route('auditor.tags.index')
            ->with('success', 'Tag "' . $name . '" berhasil dihapus secara sistem-wide.');
    }
}
