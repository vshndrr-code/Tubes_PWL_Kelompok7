@extends('layouts.app')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8 max-w-3xl mx-auto">
    <!-- Back Button & Header -->
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('auditor.categories.index') }}" 
            class="inline-flex items-center justify-center p-2 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-slate-700 hover:bg-slate-50 shadow-sm transition duration-150">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Tambah Kategori Global</h1>
            <p class="text-sm text-slate-500 mt-1">Buat kategori SDGs baru untuk digunakan seluruh masyarakat</p>
        </div>
    </div>

    <!-- Form Create Global Category (SDGs) -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
        <form action="{{ route('auditor.categories.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Nama Kategori</label>
                <input type="text" id="name" name="name" required placeholder="Contoh: Kemitraan Global (SDG 17)"
                    class="w-full rounded-2xl border-slate-200 px-4 py-3.5 text-sm focus:border-emerald-500 focus:ring-emerald-500 @error('name') border-red-500 @enderror"
                    value="{{ old('name') }}">
                @error('name')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grid for Type & Icon -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Type -->
                <div>
                    <label for="type" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Tipe Kategori</label>
                    <select id="type" name="type" required
                        class="w-full rounded-2xl border-slate-200 px-4 py-3.5 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>Pengeluaran (Expense)</option>
                        <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>Pemasukan (Income)</option>
                    </select>
                </div>

                <!-- Icon -->
                <div>
                    <label for="icon" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Ikon Kategori (FontAwesome)</label>
                    <select id="icon" name="icon"
                        class="w-full rounded-2xl border-slate-200 px-4 py-3.5 text-sm focus:border-emerald-500 focus:ring-emerald-500">
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
                </div>
            </div>

            <!-- Color -->
            <div>
                <label for="color" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Warna HEX Kategori</label>
                <div class="flex gap-4 items-center">
                    <input type="color" id="color_picker"
                        class="h-12 w-16 rounded-xl border border-slate-200 p-1 cursor-pointer"
                        value="{{ old('color') ?? '#10b981' }}"
                        oninput="document.getElementById('color').value = this.value">
                    <input type="text" id="color" name="color" placeholder="#10b981" required
                        class="flex-1 rounded-2xl border-slate-200 px-4 py-3.5 text-sm focus:border-emerald-500 focus:ring-emerald-500 @error('color') border-red-500 @enderror"
                        value="{{ old('color') ?? '#10b981' }}"
                        oninput="document.getElementById('color_picker').value = this.value">
                </div>
                @error('color')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 flex items-center justify-end gap-4">
                <a href="{{ route('auditor.categories.index') }}" 
                    class="px-5 py-3.5 border border-slate-200 text-slate-600 hover:text-slate-800 rounded-2xl hover:bg-slate-50 transition font-semibold text-sm">
                    Batal
                </a>
                <button type="submit"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3.5 px-6 rounded-2xl shadow-sm hover:shadow transition duration-150 flex items-center gap-2 text-sm">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Simpan Kategori</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
