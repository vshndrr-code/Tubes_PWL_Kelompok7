<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::where('user_id', auth()->id())
            ->withCount('transactions')
            ->orderBy('name')
            ->get();

        return view('tags.index', compact('tags'));
    }

    public function create()
    {
        return view('tags.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:150',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        Tag::create([
            'user_id' => auth()->id(),
            'name'    => $validated['name'],
            'color'   => $validated['color'] ?? '#6B7280',
        ]);

        return redirect()->route('tags.index')
            ->with('success', 'Tag "' . $validated['name'] . '" berhasil dibuat.');
    }

    public function edit(Tag $tag)
    {
        abort_if($tag->user_id !== auth()->id(), 403);

        $tag->loadCount('transactions');

        return view('tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        abort_if($tag->user_id !== auth()->id(), 403);

        $validated = $request->validate([
            'name'  => 'required|string|max:150',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $tag->update([
            'name'  => $validated['name'],
            'color' => $validated['color'] ?? $tag->color ?? '#6B7280',
        ]);

        return redirect()->route('tags.index')
            ->with('success', 'Tag "' . $validated['name'] . '" berhasil diperbarui.');
    }

    public function destroy(Tag $tag)
    {
        abort_if($tag->user_id !== auth()->id(), 403);

        $name = $tag->name;
        $tag->delete();

        return redirect()->route('tags.index')
            ->with('success', 'Tag "' . $name . '" berhasil dihapus.');
    }
}
