<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Akun Baru</h2>
                <p class="text-sm text-gray-500">Buat akun baru untuk dompet atau rekening Anda.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-5">
                    <h3 class="text-lg font-semibold text-gray-900">Tambah Akun Baru</h3>
                    <p class="text-sm text-gray-500 mt-1">Buat akun baru untuk dompet atau rekening Anda.</p>
                </div>

                <form action="{{ route('accounts.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    <div class="grid gap-5 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Akun</label>
                            <input id="name" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100" placeholder="Bank BCA, Cash, DANA" />
                            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Akun</label>
                            <select id="type" name="type" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 appearance-none bg-white cursor-pointer">
                                <option value="">Pilih tipe akun</option>
                                <option value="cash" {{ old('type') == 'cash' ? 'selected' : '' }}>💵 Cash</option>
                                <option value="bank" {{ old('type') == 'bank' ? 'selected' : '' }}>🏦 Bank</option>
                                <option value="credit" {{ old('type') == 'credit' ? 'selected' : '' }}>💳 Kartu Kredit</option>
                                <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>📱 E-wallet</option>
                            </select>
                            @error('type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="balance" class="block text-sm font-medium text-gray-700 mb-2">Saldo Awal (Rp)</label>
                            <input id="balance" name="balance" type="number" step="0.01" min="0" value="{{ old('balance', '0.00') }}" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100" placeholder="1000000" />
                            @error('balance')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:justify-end pt-5 border-t border-gray-200">
                        <a href="{{ route('accounts.index') }}" class="inline-flex justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">Batal</a>
                        <button type="submit" class="inline-flex justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Simpan Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
