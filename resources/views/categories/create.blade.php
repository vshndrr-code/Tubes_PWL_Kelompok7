<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Kategori Baru</h2>
                <p class="text-sm text-gray-500">Buat kategori agar transaksi Anda lebih teratur.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-lg">
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-4">
                    <h3 class="text-xl font-semibold text-white">Tambah Kategori Baru</h3>
                    <p class="text-emerald-100">Buat kategori agar transaksi Anda lebih teratur.</p>
                </div>

                <form action="{{ route('categories.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Nama Kategori</label>
                            <input id="name" name="name" value="{{ old('name') }}" required class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" placeholder="Makan, Gaji, Transport" />
                            @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-semibold text-slate-700 mb-2">Tipe Kategori</label>
                            <select id="type" name="type" required class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                                <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>💸 Pengeluaran</option>
                                <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>💰 Pemasukan</option>
                                <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>📋 Lainnya</option>
                            </select>
                            @error('type')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="icon" class="block text-sm font-semibold text-slate-700 mb-2">Ikon</label>
                            <select id="icon" name="icon" class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                                <option value="utensils" {{ old('icon', 'utensils') == 'utensils' ? 'selected' : '' }}>🍽️ Makan</option>
                                <option value="car" {{ old('icon') == 'car' ? 'selected' : '' }}>🚗 Transport</option>
                                <option value="shopping-bag" {{ old('icon') == 'shopping-bag' ? 'selected' : '' }}>🛍️ Belanja</option>
                                <option value="wifi" {{ old('icon') == 'wifi' ? 'selected' : '' }}>📶 Internet</option>
                                <option value="gift" {{ old('icon') == 'gift' ? 'selected' : '' }}>🎁 Hadiah</option>
                                <option value="money-bill-wave" {{ old('icon') == 'money-bill-wave' ? 'selected' : '' }}>💵 Gaji</option>
                                <option value="chart-line" {{ old('icon') == 'chart-line' ? 'selected' : '' }}>📈 Investasi</option>
                                <option value="tag" {{ old('icon') == 'tag' ? 'selected' : '' }}>🏷️ Lainnya</option>
                            </select>
                            @error('icon')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:justify-end pt-4 border-t border-slate-200">
                        <a href="{{ route('categories.index') }}" class="inline-flex justify-center rounded-2xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</a>
                        <button type="submit" class="inline-flex justify-center rounded-2xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
