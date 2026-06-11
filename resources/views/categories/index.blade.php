<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kategori Saya</h2>
                <p class="text-sm text-gray-500">Lihat dan edit kategori pengeluaran atau pemasukan Anda sendiri.</p>
            </div>
            <div>
                <a href="{{ route('categories.create') }}" class="inline-flex items-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/10 transition hover:bg-emerald-700">
                    Tambah Kategori
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 rounded-3xl border border-green-200 bg-green-50 px-6 py-4 text-green-700 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if($categories->isEmpty())
                <div class="rounded-3xl border border-dashed border-gray-300 bg-white p-12 text-center shadow-sm">
                    <div class="mx-auto h-24 w-24 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                        <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <p class="text-gray-500 mb-4">Belum ada kategori. Tambahkan kategori agar transaksi lebih terorganisir.</p>
                    <a href="{{ route('categories.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Buat Kategori Baru
                    </a>
                </div>
            @else
                <div class="grid gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8">
                    @foreach($categories as $category)
                        @php
                            $typeColors = [
                                'expense' => 'bg-red-500',
                                'income' => 'bg-green-500',
                                'other' => 'bg-gray-500',
                            ];
                            $typeBgColors = [
                                'expense' => 'bg-red-50',
                                'income' => 'bg-green-50',
                                'other' => 'bg-gray-50',
                            ];
                            $typeTextColors = [
                                'expense' => 'text-red-700',
                                'income' => 'text-green-700',
                                'other' => 'text-gray-700',
                            ];
                            $categoryColor = $typeColors[$category->type] ?? 'bg-gray-500';
                            $categoryBgColor = $typeBgColors[$category->type] ?? 'bg-gray-50';
                            $categoryTextColor = $typeTextColors[$category->type] ?? 'text-gray-700';
                        @endphp

                        <div class="group relative bg-white rounded-2xl border border-gray-200 p-4 shadow-sm transition hover:shadow-md hover:-translate-y-1">
                            <div class="flex flex-col items-center text-center space-y-3">
                                <div class="relative">
                                    <div class="h-16 w-16 rounded-full flex items-center justify-center shadow-lg"
                                        style="background-color: {{ $category->color ?? ($category->type === 'income' ? '#10b981' : '#ef4444') }}">
                                        <i class="fa-solid fa-{{ $category->icon ?? 'tag' }} text-white text-2xl"></i>
                                    </div>
                                    <div class="absolute -top-1 -right-1 flex gap-1">
                                        <a href="{{ route('categories.edit', $category) }}" class="p-1 rounded-full bg-white text-gray-600 hover:text-gray-800 shadow-sm transition">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Hapus kategori ini?')" class="p-1 rounded-full bg-white text-red-600 hover:text-red-800 shadow-sm transition">
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H6.862a2 2 0 01-1.995-1.858L4 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <h3 class="text-sm font-semibold text-gray-900 leading-tight">{{ $category->name }}</h3>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $categoryBgColor }} {{ $categoryTextColor }}">
                                        {{ ucfirst($category->type) }}
                                    </span>
                                </div>

                                <a href="{{ route('budgetings.create', ['category' => $category->id]) }}" class="mt-3 inline-flex items-center justify-center gap-1 rounded-lg bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700 transition w-full">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Tambah Budget
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
