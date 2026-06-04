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
                                    <div class="h-16 w-16 rounded-full {{ $categoryColor }} flex items-center justify-center shadow-lg">
                                        @switch($category->icon ?? 'tag')
                                            @case('utensils')
                                                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4" />
                                                </svg>
                                                @break
                                            @case('car')
                                                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                                </svg>
                                                @break
                                            @case('shopping-bag')
                                                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                                </svg>
                                                @break
                                            @case('wifi')
                                                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-6.938-6.938a9 9 0 1112.776 0M12 14a3 3 0 100-6 3 3 0 000 6z" />
                                                </svg>
                                                @break
                                            @case('gift')
                                                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                @break
                                            @case('money-bill-wave')
                                                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                                </svg>
                                                @break
                                            @case('chart-line')
                                                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                                </svg>
                                                @break
                                            @default
                                                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                </svg>
                                        @endswitch
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
