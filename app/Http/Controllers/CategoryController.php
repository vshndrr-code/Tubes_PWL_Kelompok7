<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,NULL,id,user_id,' . auth()->id(),
            'type' => 'required|in:expense,income',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|size:7',
        ], [
            'name.unique' => 'Nama kategori ini sudah ada. Silakan gunakan nama yang berbeda.',
        ]);

        Category::create(array_merge($data, ['user_id' => auth()->id()]));

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        $this->authorizeOwner($category);

        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $this->authorizeOwner($category);

        $data = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id . ',id,user_id,' . auth()->id(),
            'type' => 'required|in:expense,income',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|size:7',
        ], [
            'name.unique' => 'Nama kategori ini sudah ada. Silakan gunakan nama yang berbeda.',
        ]);

        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $this->authorizeOwner($category);

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }

    private function authorizeOwner(Category $category): void
    {
        if ($category->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
