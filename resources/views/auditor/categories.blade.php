@extends('layouts.app')

@push('head')
<style>
    @keyframes soft-enter {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (prefers-reduced-motion: no-preference) {
        .ui-reveal {
            animation: soft-enter .42s ease-out both;
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-[#f6f7f9] text-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-10 lg:px-8">
        
        <!-- Header -->
        <div class="mb-7 flex flex-col gap-4 border-b border-slate-200 pb-6 lg:flex-row lg:items-end lg:justify-between ui-reveal">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Moderasi Kategori</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Kategori Global (SDGs)</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                    Kelola skema pengkategorian transaksi global SDGs yang dapat diakses oleh seluruh pengguna.
                </p>
            </div>
            <div class="flex items-center gap-3 self-start lg:self-auto">
                <a href="{{ route('auditor.categories.create') }}" 
                    class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/10 hover:bg-emerald-700 transition duration-150">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah Kategori Global</span>
                </a>
            </div>
        </div>

        <!-- Alert Success -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-xl shadow-sm flex items-center justify-between ui-reveal">
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Global Categories List with Tabs -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 ui-reveal" x-data="{ activeTab: 'expense' }">
            
            <!-- Tab Navigation Buttons -->
            <div class="flex border-b border-slate-100 mb-6 gap-2">
                <button @click="activeTab = 'expense'"
                    :class="activeTab === 'expense' ? 'border-red-500 text-red-600 border-b-2 font-bold' : 'text-slate-400 hover:text-slate-600'"
                    class="py-3 px-4 text-sm font-semibold transition focus:outline-none flex items-center gap-2">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-red-500"></span>
                    <span>Pengeluaran (Expense)</span>
                    <span class="bg-red-50 text-red-700 text-xs px-2.5 py-0.5 rounded-full font-medium">
                        {{ $globalCategories->where('type', 'expense')->count() }}
                    </span>
                </button>
                <button @click="activeTab = 'income'"
                    :class="activeTab === 'income' ? 'border-green-500 text-green-600 border-b-2 font-bold' : 'text-slate-400 hover:text-slate-600'"
                    class="py-3 px-4 text-sm font-semibold transition focus:outline-none flex items-center gap-2">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-green-500"></span>
                    <span>Pemasukan (Income)</span>
                    <span class="bg-green-50 text-green-700 text-xs px-2.5 py-0.5 rounded-full font-medium">
                        {{ $globalCategories->where('type', 'income')->count() }}
                    </span>
                </button>
            </div>

            <!-- Tab 1: Expense List -->
            <div x-show="activeTab === 'expense'">
                @php $expenseCategories = $globalCategories->where('type', 'expense'); @endphp
                @if($expenseCategories->isEmpty())
                    <div class="text-center py-16 bg-slate-50/50 rounded-3xl border border-dashed border-slate-200">
                        <div class="mx-auto h-16 w-16 rounded-full bg-slate-100 flex items-center justify-center mb-4 text-slate-400">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2M9 5h6m-6 8h6" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900">Belum ada Kategori Pengeluaran</h3>
                        <p class="mt-1 text-xs text-slate-500">Kategori global bertipe pengeluaran akan muncul di sini.</p>
                    </div>
                @else
                    <div class="overflow-hidden rounded-2xl border border-slate-100">
                        <table class="w-full text-left text-sm text-slate-700">
                            <thead class="text-xs font-semibold text-slate-400 uppercase tracking-[0.18em] bg-slate-50">
                                <tr>
                                    <th class="px-6 py-4">Nama Kategori</th>
                                    <th class="px-6 py-4">Ikon & Warna</th>
                                    <th class="px-6 py-4">Kode Warna</th>
                                    <th class="px-6 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach($expenseCategories as $category)
                                    <tr class="hover:bg-slate-50/80 transition duration-150">
                                        <td class="px-6 py-4 font-semibold text-slate-800">{{ $category->name }}</td>
                                        <td class="px-6 py-4 flex items-center gap-3">
                                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl text-white font-bold shadow-sm"
                                                style="background-color: {{ $category->color ?? '#ef4444' }}">
                                                <i class="fa-solid fa-{{ $category->icon ?? 'tag' }} text-sm"></i>
                                            </span>
                                            <span class="text-xs text-slate-500 font-medium">fa-{{ $category->icon ?? 'tag' }}</span>
                                        </td>
                                        <td class="px-6 py-4 font-mono text-xs text-slate-600">{{ $category->color }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <form action="{{ route('auditor.categories.destroy', $category) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori global \'{{ $category->name }}\'? Pengguna tidak akan dapat memilih kategori ini lagi.');"
                                                class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 hover:bg-red-100 font-semibold py-2 px-3.5 rounded-xl transition duration-150 text-xs">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    <span>Hapus</span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Tab 2: Income List -->
            <div x-show="activeTab === 'income'">
                @php $incomeCategories = $globalCategories->where('type', 'income'); @endphp
                @if($incomeCategories->isEmpty())
                    <div class="text-center py-16 bg-slate-50/50 rounded-3xl border border-dashed border-slate-200">
                        <div class="mx-auto h-16 w-16 rounded-full bg-slate-100 flex items-center justify-center mb-4 text-slate-400">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2M9 5h6m-6 8h6" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900">Belum ada Kategori Pemasukan</h3>
                        <p class="mt-1 text-xs text-slate-500">Kategori global bertipe pemasukan akan muncul di sini.</p>
                    </div>
                @else
                    <div class="overflow-hidden rounded-2xl border border-slate-100">
                        <table class="w-full text-left text-sm text-slate-700">
                            <thead class="text-xs font-semibold text-slate-400 uppercase tracking-[0.18em] bg-slate-50">
                                <tr>
                                    <th class="px-6 py-4">Nama Kategori</th>
                                    <th class="px-6 py-4">Ikon & Warna</th>
                                    <th class="px-6 py-4">Kode Warna</th>
                                    <th class="px-6 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach($incomeCategories as $category)
                                    <tr class="hover:bg-slate-50/80 transition duration-150">
                                        <td class="px-6 py-4 font-semibold text-slate-800">{{ $category->name }}</td>
                                        <td class="px-6 py-4 flex items-center gap-3">
                                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl text-white font-bold shadow-sm"
                                                style="background-color: {{ $category->color ?? '#10b981' }}">
                                                <i class="fa-solid fa-{{ $category->icon ?? 'tag' }} text-sm"></i>
                                            </span>
                                            <span class="text-xs text-slate-500 font-medium">fa-{{ $category->icon ?? 'tag' }}</span>
                                        </td>
                                        <td class="px-6 py-4 font-mono text-xs text-slate-600">{{ $category->color }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <form action="{{ route('auditor.categories.destroy', $category) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori global \'{{ $category->name }}\'? Pengguna tidak akan dapat memilih kategori ini lagi.');"
                                                class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 hover:bg-red-100 font-semibold py-2 px-3.5 rounded-xl transition duration-150 text-xs">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    <span>Hapus</span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
