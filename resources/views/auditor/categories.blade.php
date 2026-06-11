@extends('layouts.app')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Moderasi Kategori</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola dan tambah kategori global SDGs untuk seluruh pengguna</p>
        </div>
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl px-4 py-2 flex items-center gap-2">
            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-xs font-semibold text-emerald-800">Manajemen Konten Kategori</span>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form Create Global Category (SDGs) -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 lg:col-span-1 h-fit">
            <h2 class="text-xl font-bold text-slate-800 mb-5">Tambah Kategori Global</h2>
            <form action="{{ route('auditor.categories.store') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Nama Kategori</label>
                    <input type="text" id="name" name="name" required placeholder="Contoh: Energi Bersih & Terjangkau"
                        class="w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500 @error('name') border-red-500 @enderror"
                        value="{{ old('name') }}">
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Tipe Kategori</label>
                    <select id="type" name="type" required
                        class="w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>Pengeluaran (Expense)</option>
                        <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>Pemasukan (Income)</option>
                    </select>
                </div>

                <!-- Icon -->
                <div>
                    <label for="icon" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Ikon Kategori (FontAwesome)</label>
                    <select id="icon" name="icon"
                        class="w-full rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="leaf">Daun (SDG 13/15) - leaf</option>
                        <option value="bolt">Energi (SDG 7) - bolt</option>
                        <option value="heart-pulse">Kesehatan (SDG 3) - heart-pulse</option>
                        <option value="graduation-cap">Pendidikan (SDG 4) - graduation-cap</option>
                        <option value="droplet">Air Bersih (SDG 6) - droplet</option>
                        <option value="briefcase">Pekerjaan Layak (SDG 8) - briefcase</option>
                        <option value="globe">Kemitraan Global (SDG 17) - globe</option>
                        <option value="money-bill-wave">Uang - money-bill-wave</option>
                        <option value="piggy-bank">Celengan - piggy-bank</option>
                        <option value="chart-line">Investasi - chart-line</option>
                        <option value="hand-holding-dollar">Dana Sosial - hand-holding-dollar</option>
                        <option value="shield-halved">Perdamaian - shield-halved</option>
                        <option value="utensils">Tanpa Kelaparan (SDG 2) - utensils</option>
                        <option value="house">Kota Berkelanjutan (SDG 11) - house</option>
                        <option value="trash">Konsumsi Bertanggung Jawab (SDG 12) - trash</option>
                        <option value="lightbulb">Inovasi (SDG 9) - lightbulb</option>
                    </select>
                    <p class="text-[11px] text-slate-400 mt-1">Ikon akan dirender di sisi client menggunakan FontAwesome.</p>
                </div>

                <!-- Color -->
                <div>
                    <label for="color" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Warna HEX Kategori</label>
                    <div class="flex gap-3 items-center">
                        <input type="color" id="color_picker"
                            class="h-11 w-14 rounded-xl border border-slate-200 p-1 cursor-pointer"
                            value="{{ old('color') ?? '#10b981' }}"
                            oninput="document.getElementById('color').value = this.value">
                        <input type="text" id="color" name="color" placeholder="#10b981" required
                            class="flex-1 rounded-2xl border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500 @error('color') border-red-500 @enderror"
                            value="{{ old('color') ?? '#10b981' }}"
                            oninput="document.getElementById('color_picker').value = this.value">
                    </div>
                    @error('color')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 px-4 rounded-2xl shadow-sm hover:shadow transition duration-150 flex items-center justify-center gap-2">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Simpan Kategori</span>
                </button>
            </form>
        </div>

        <!-- Global Categories List with Tabs -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 lg:col-span-2" x-data="{ activeTab: 'expense' }">
            <h2 class="text-xl font-bold text-slate-800 mb-5">Daftar Kategori Global (SDGs) Aktif</h2>
            
            <!-- Tab Navigation Buttons -->
            <div class="flex border-b border-slate-100 mb-6 gap-2">
                <button @click="activeTab = 'expense'"
                    :class="activeTab === 'expense' ? 'border-red-500 text-red-600 border-b-2 font-bold' : 'text-slate-400 hover:text-slate-600'"
                    class="py-3 px-4 text-sm font-semibold transition focus:outline-none flex items-center gap-2">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-red-500"></span>
                    <span>Pengeluaran (Expense)</span>
                    <span class="bg-red-50 text-red-700 text-xs px-2 py-0.5 rounded-full font-medium">
                        {{ $globalCategories->where('type', 'expense')->count() }}
                    </span>
                </button>
                <button @click="activeTab = 'income'"
                    :class="activeTab === 'income' ? 'border-green-500 text-green-600 border-b-2 font-bold' : 'text-slate-400 hover:text-slate-600'"
                    class="py-3 px-4 text-sm font-semibold transition focus:outline-none flex items-center gap-2">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-green-500"></span>
                    <span>Pemasukan (Income)</span>
                    <span class="bg-green-50 text-green-700 text-xs px-2 py-0.5 rounded-full font-medium">
                        {{ $globalCategories->where('type', 'income')->count() }}
                    </span>
                </button>
            </div>

            <!-- Tab 1: Expense List -->
            <div x-show="activeTab === 'expense'">
                @php $expenseCategories = $globalCategories->where('type', 'expense'); @endphp
                @if($expenseCategories->isEmpty())
                    <div class="text-center py-12 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2M9 5h6m-6 8h6" />
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-slate-900">Belum ada Kategori Pengeluaran</h3>
                        <p class="mt-1 text-xs text-slate-500">Kategori global bertipe pengeluaran akan muncul di sini.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-700">
                            <thead class="text-xs font-semibold text-slate-400 uppercase tracking-wider bg-slate-50">
                                <tr>
                                    <th class="px-6 py-4 rounded-l-2xl">Nama Kategori</th>
                                    <th class="px-6 py-4">Ikon & Warna</th>
                                    <th class="px-6 py-4">Kode Warna</th>
                                    <th class="px-6 py-4 rounded-r-2xl text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($expenseCategories as $category)
                                    <tr class="hover:bg-slate-50 transition duration-150">
                                        <td class="px-6 py-4 font-semibold text-slate-800">{{ $category->name }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-white font-bold"
                                                style="background-color: {{ $category->color ?? '#ef4444' }}">
                                                <i class="fa-solid fa-{{ $category->icon ?? 'tag' }}"></i>
                                            </span>
                                            <span class="text-xs text-slate-500 ml-2">fa-{{ $category->icon ?? 'tag' }}</span>
                                        </td>
                                        <td class="px-6 py-4 font-mono text-xs text-slate-600">{{ $category->color }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <form action="{{ route('auditor.categories.destroy', $category) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori global \'{{ $category->name }}\'? Pengguna tidak akan dapat memilih kategori ini lagi.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1 bg-red-50 text-red-600 hover:bg-red-100 font-semibold py-1.5 px-3 rounded-xl transition duration-150 text-xs">
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
                    <div class="text-center py-12 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2M9 5h6m-6 8h6" />
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-slate-900">Belum ada Kategori Pemasukan</h3>
                        <p class="mt-1 text-xs text-slate-500">Kategori global bertipe pemasukan akan muncul di sini.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-700">
                            <thead class="text-xs font-semibold text-slate-400 uppercase tracking-wider bg-slate-50">
                                <tr>
                                    <th class="px-6 py-4 rounded-l-2xl">Nama Kategori</th>
                                    <th class="px-6 py-4">Ikon & Warna</th>
                                    <th class="px-6 py-4">Kode Warna</th>
                                    <th class="px-6 py-4 rounded-r-2xl text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($incomeCategories as $category)
                                    <tr class="hover:bg-slate-50 transition duration-150">
                                        <td class="px-6 py-4 font-semibold text-slate-800">{{ $category->name }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-white font-bold"
                                                style="background-color: {{ $category->color ?? '#10b981' }}">
                                                <i class="fa-solid fa-{{ $category->icon ?? 'tag' }}"></i>
                                            </span>
                                            <span class="text-xs text-slate-500 ml-2">fa-{{ $category->icon ?? 'tag' }}</span>
                                        </td>
                                        <td class="px-6 py-4 font-mono text-xs text-slate-600">{{ $category->color }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <form action="{{ route('auditor.categories.destroy', $category) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori global \'{{ $category->name }}\'? Pengguna tidak akan dapat memilih kategori ini lagi.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1 bg-red-50 text-red-600 hover:bg-red-100 font-semibold py-1.5 px-3 rounded-xl transition duration-150 text-xs">
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
