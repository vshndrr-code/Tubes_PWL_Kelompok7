<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Akun</h2>
                <p class="text-sm text-gray-500">Perbarui detail akun Anda dengan mudah.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-5">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Akun</h3>
                    <p class="text-sm text-gray-500 mt-1">Perbarui detail akun Anda dengan mudah.</p>
                </div>

                <form action="{{ route('accounts.update', $account) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-5 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Akun</label>
                            <input id="name" name="name" value="{{ old('name', $account->name) }}" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100" />
                            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Akun</label>
                            <select id="type" name="type" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 appearance-none bg-white cursor-pointer">
                                <option value="cash" {{ old('type', $account->type) == 'cash' ? 'selected' : '' }}>💵 Cash</option>
                                <option value="bank" {{ old('type', $account->type) == 'bank' ? 'selected' : '' }}>🏦 Bank</option>
                                <option value="credit" {{ old('type', $account->type) == 'credit' ? 'selected' : '' }}>💳 Kartu Kredit</option>
                                <option value="other" {{ old('type', $account->type) == 'other' ? 'selected' : '' }}>📱 E-wallet</option>
                            </select>
                            @error('type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="balance" class="block text-sm font-medium text-gray-700 mb-2">Saldo Akun (Rp)</label>
                            <input id="balance" name="balance" type="number" step="0.01" min="0" value="{{ old('balance', $account->balance) }}" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100" />
                            @error('balance')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:justify-end pt-5 border-t border-gray-200">
                        <a href="{{ route('accounts.index') }}" class="inline-flex justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">Batal</a>
                        <button type="submit" class="inline-flex justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Perbarui Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
