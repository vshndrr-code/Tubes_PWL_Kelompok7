<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Tambah Akun Baru</h2>
            <p class="text-sm text-slate-500">Buat dompet, rekening, kartu, atau e-wallet baru.</p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.28em] text-slate-400">Wallet Setup</p>
                    <h1 class="mt-2 text-4xl font-extrabold tracking-tight text-slate-950">Tambah Akun</h1>
                    <p class="mt-2 text-slate-500">Tambahkan sumber dana baru agar transaksi lebih mudah dipantau.</p>
                </div>

                <a href="{{ route('accounts.index') }}"
                    class="inline-flex h-11 items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    Kembali
                </a>
            </div>

            <div class="grid gap-6 lg:grid-cols-[1fr_0.72fr]">
                <div class="overflow-hidden rounded-[32px] border border-white/70 bg-white/90 shadow-xl shadow-slate-900/5 ring-1 ring-slate-200/70">
                    <div class="border-b border-slate-100 bg-white px-6 py-5">
                        <h3 class="text-lg font-extrabold text-slate-950">Detail Akun</h3>
                        <p class="mt-1 text-sm text-slate-500">Isi informasi dasar akun keuangan Anda.</p>
                    </div>

                    <form action="{{ route('accounts.store') }}" method="POST" class="space-y-7 p-6">
                        @csrf

                        <div>
                            <label for="name" class="mb-2 block text-sm font-bold text-slate-700">Nama Akun</label>
                            <input id="name" name="name" value="{{ old('name') }}" required
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-100"
                                placeholder="Bank BCA, Cash, DANA" />
                            @error('name')
                                <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <p class="mb-3 block text-sm font-bold text-slate-700">Tipe Akun</p>

                            <div class="grid gap-3 sm:grid-cols-2">
                                @php
                                    $types = [
                                        'cash' => ['label' => 'Cash', 'desc' => 'Uang tunai harian', 'color' => 'peer-checked:border-emerald-400 peer-checked:bg-emerald-50'],
                                        'bank' => ['label' => 'Bank', 'desc' => 'Rekening tabungan', 'color' => 'peer-checked:border-blue-400 peer-checked:bg-blue-50'],
                                        'credit' => ['label' => 'Kartu Kredit', 'desc' => 'Limit dan cicilan', 'color' => 'peer-checked:border-rose-400 peer-checked:bg-rose-50'],
                                        'other' => ['label' => 'E-wallet', 'desc' => 'Dompet digital', 'color' => 'peer-checked:border-violet-400 peer-checked:bg-violet-50'],
                                    ];
                                @endphp

                                @foreach ($types as $value => $type)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="type" value="{{ $value }}" class="peer sr-only"
                                            @checked(old('type', 'cash') === $value)>

                                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 transition {{ $type['color'] }}">
                                            <div class="flex items-center justify-between gap-3">
                                                <div>
                                                    <p class="font-extrabold text-slate-900">{{ $type['label'] }}</p>
                                                    <p class="mt-1 text-xs font-medium text-slate-500">{{ $type['desc'] }}</p>
                                                </div>

                                                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-white text-slate-500 shadow-sm ring-1 ring-slate-200">
                                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M20 6L9 17l-5-5" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            @error('type')
                                <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="balance" class="mb-2 block text-sm font-bold text-slate-700">Saldo Awal</label>
                            <div class="flex overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 transition focus-within:border-blue-400 focus-within:bg-white focus-within:ring-4 focus-within:ring-blue-100">
                                <span class="flex items-center border-r border-slate-200 px-4 text-sm font-extrabold text-slate-500">Rp</span>
                                <input id="balance" name="balance" type="number" step="0.01" min="0"
                                    value="{{ old('balance', '0.00') }}" required
                                    class="w-full border-0 bg-transparent px-4 py-3 text-sm font-semibold text-slate-900 outline-none focus:ring-0"
                                    placeholder="1000000" />
                            </div>
                            @error('balance')
                                <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end">
                            <a href="{{ route('accounts.index') }}"
                                class="inline-flex h-12 items-center justify-center rounded-2xl border border-slate-200 bg-white px-6 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
                                Batal
                            </a>

                            <button type="submit"
                                class="inline-flex h-12 items-center justify-center gap-2 rounded-2xl bg-slate-950 px-6 text-sm font-bold text-white shadow-lg shadow-slate-900/15 transition hover:bg-slate-800">
                                Simpan Akun
                            </button>
                        </div>
                    </form>
                </div>

                <aside class="rounded-[32px] border border-white/70 bg-white/75 p-6 shadow-xl shadow-slate-900/5 ring-1 ring-slate-200/70">
                    <p class="text-xs font-bold uppercase tracking-[0.28em] text-slate-400">Preview</p>
                    <div class="mt-5 rounded-[28px] bg-slate-950 p-5 text-white">
                        <p class="text-sm text-slate-400">Akun baru</p>
                        <p class="mt-3 text-2xl font-extrabold">Siap dicatat</p>
                        <p class="mt-8 text-sm text-slate-400">Saldo awal</p>
                        <p class="mt-1 text-3xl font-extrabold">Rp0</p>
                    </div>

                    <div class="mt-5 space-y-3 text-sm text-slate-500">
                        <p class="font-semibold text-slate-700">Tips kecil:</p>
                        <p>Gunakan nama yang mudah dikenali seperti “Bank Mandiri Payroll” atau “Cash Harian”.</p>
                        <p>Pilih tipe akun yang tepat supaya ringkasan saldo dan filter transaksi lebih rapi.</p>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>