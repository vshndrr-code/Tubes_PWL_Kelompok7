<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Budget</h2>
                <p class="text-sm text-gray-500">Atur batas pengeluaran untuk kategori tertentu setiap bulan.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-lg">
                <div class="bg-gradient-to-r from-sky-500 to-sky-600 px-6 py-4">
                    <h3 class="text-xl font-semibold text-white">Tambah Budget</h3>
                    <p class="text-sky-100">Masukkan nilai batas untuk kategori pengeluaran.</p>
                </div>

                <form action="{{ route('budgets.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label for="category_id" class="block text-sm font-semibold text-slate-700 mb-2">Kategori</label>
                            <select id="category_id" name="category_id" required class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                                <option value="">Pilih kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="limit_amount" class="block text-sm font-semibold text-slate-700 mb-2">Limit Anggaran</label>
                            <input id="limit_amount" name="limit_amount" type="number" step="0.01" min="0" value="{{ old('limit_amount') }}" required class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100" placeholder="500000" />
                            @error('limit_amount')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="month" class="block text-sm font-semibold text-slate-700 mb-2">Bulan</label>
                            <input id="month" name="month" type="number" min="1" max="12" value="{{ old('month', now()->month) }}" required class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100" />
                            @error('month')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="year" class="block text-sm font-semibold text-slate-700 mb-2">Tahun</label>
                            <input id="year" name="year" type="number" min="2000" value="{{ old('year', now()->year) }}" required class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100" />
                            @error('year')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:justify-end pt-4 border-t border-slate-200">
                        <a href="{{ route('budgets.index') }}" class="inline-flex justify-center rounded-2xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</a>
                        <button type="submit" class="inline-flex justify-center rounded-2xl bg-sky-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-sky-700">Simpan Budget</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
