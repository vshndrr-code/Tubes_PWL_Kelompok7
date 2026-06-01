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

        .ui-card,
        .ui-button {
            transition:
                transform .18s ease,
                box-shadow .18s ease,
                border-color .18s ease,
                background-color .18s ease,
                color .18s ease;
        }

        .ui-card:hover,
        .ui-button:hover {
            transform: translateY(-2px);
        }
    }
</style>
@endpush

<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-950">Transfer Antar Akun</h2>
            <p class="mt-1 text-sm text-slate-500">Pindahkan saldo antar rekening tanpa keluar dari dashboard.</p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-[#f6f7f9] py-6 text-slate-900 sm:py-10">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="mb-7 flex flex-col gap-4 border-b border-slate-200 pb-6 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Pindah Saldo</p>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Transfer Saldo</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                        Pindahkan saldo dari satu akun ke akun lain dengan catatan yang tetap rapi.
                    </p>
                </div>

                <a href="{{ route('accounts.index') }}"
                    class="ui-button inline-flex h-11 items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm hover:border-slate-300 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300 focus:ring-offset-2 focus:ring-offset-[#f6f7f9]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5" />
                        <path d="M12 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
            </div>

            @if (session('success'))
                <div class="mb-5 rounded-lg border border-emerald-200 bg-white px-4 py-3 text-sm font-medium text-emerald-800 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($accounts->count() < 2)
                <div class="mb-5 rounded-lg border border-amber-200 bg-amber-50 px-4 py-4 text-amber-900 shadow-sm">
                    <p class="font-semibold">Transfer belum bisa digunakan</p>
                    <p class="mt-1 text-sm">Tambahkan minimal dua akun untuk dapat memindahkan saldo.</p>
                </div>
            @endif

            <div class="grid gap-5 lg:grid-cols-[1fr_0.64fr]">
                <div class="ui-reveal overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4 sm:px-6">
                        <h3 class="text-lg font-semibold text-slate-950">Detail Transfer</h3>
                        <p class="mt-1 text-sm text-slate-500">Pilih akun sumber, akun tujuan, lalu masukkan nominal.</p>
                    </div>

                    <form action="{{ route('accounts.transfer.store') }}" method="POST" class="space-y-6 p-5 sm:p-6">
                        @csrf

                        <div class="grid gap-4 sm:grid-cols-[1fr_auto_1fr] sm:items-end">
                            <div>
                                <label for="from_account_id" class="mb-2 block text-sm font-semibold text-slate-700">Dari Akun</label>
                                <select id="from_account_id" name="from_account_id" required
                                    class="h-11 w-full rounded-lg border border-slate-200 bg-slate-50 px-3.5 text-sm font-medium text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100">
                                    <option value="">Pilih akun sumber</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}" {{ old('from_account_id') == $account->id ? 'selected' : '' }}>
                                            {{ $account->name }} - {{ ucfirst($account->type) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('from_account_id')
                                    <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="hidden pb-1 sm:flex">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-950 text-white shadow-sm shadow-slate-900/10">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14" />
                                        <path d="M13 6l6 6-6 6" />
                                    </svg>
                                </div>
                            </div>

                            <div>
                                <label for="to_account_id" class="mb-2 block text-sm font-semibold text-slate-700">Ke Akun</label>
                                <select id="to_account_id" name="to_account_id" required
                                    class="h-11 w-full rounded-lg border border-slate-200 bg-slate-50 px-3.5 text-sm font-medium text-slate-900 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100">
                                    <option value="">Pilih akun tujuan</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}" {{ old('to_account_id') == $account->id ? 'selected' : '' }}>
                                            {{ $account->name }} - {{ ucfirst($account->type) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('to_account_id')
                                    <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="amount" class="mb-2 block text-sm font-semibold text-slate-700">Jumlah Transfer</label>
                            <div class="flex h-11 overflow-hidden rounded-lg border border-slate-200 bg-slate-50 transition focus-within:border-slate-400 focus-within:bg-white focus-within:ring-2 focus-within:ring-slate-100">
                                <span class="flex items-center border-r border-slate-200 px-3.5 text-sm font-semibold text-slate-500">Rp</span>
                                <input id="amount" name="amount" type="number" step="0.01" min="0.01"
                                    value="{{ old('amount') }}" placeholder="1000000" required
                                    class="w-full border-0 bg-transparent px-3.5 text-sm font-medium text-slate-900 outline-none focus:ring-0" />
                            </div>
                            @error('amount')
                                <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">
                            <a href="{{ route('accounts.index') }}"
                                class="ui-button inline-flex h-11 items-center justify-center rounded-lg border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-700 shadow-sm hover:border-slate-300 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300">
                                Batal
                            </a>

                            <button type="submit"
                                class="ui-button inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-slate-950 px-5 text-sm font-semibold text-white shadow-sm shadow-slate-900/10 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60 disabled:hover:translate-y-0"
                                @disabled($accounts->count() < 2)>
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14" />
                                    <path d="M13 6l6 6-6 6" />
                                </svg>
                                Transfer Sekarang
                            </button>
                        </div>
                    </form>
                </div>

                <aside class="ui-reveal rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-6" style="animation-delay: .06s">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Alur Transfer</p>

                    <div class="mt-5 space-y-3">
                        <div class="rounded-lg bg-slate-950 p-5 text-white shadow-sm shadow-slate-900/10">
                            <div class="flex items-start gap-3">
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/10 text-sm font-semibold ring-1 ring-white/10">1</div>
                                <div>
                                    <p class="text-lg font-semibold">Pilih akun sumber</p>
                                    <p class="mt-1 text-sm leading-6 text-slate-400">Saldo akan dikurangi dari akun ini.</p>
                                </div>
                            </div>
                        </div>

                        <div class="ui-card rounded-lg border border-slate-200 bg-slate-50 p-5">
                            <div class="flex items-start gap-3">
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-sm font-semibold text-slate-600 ring-1 ring-slate-200">2</div>
                                <div>
                                    <p class="text-lg font-semibold text-slate-950">Pilih akun tujuan</p>
                                    <p class="mt-1 text-sm leading-6 text-slate-500">Saldo akan ditambahkan ke akun tujuan.</p>
                                </div>
                            </div>
                        </div>

                        <div class="ui-card rounded-lg border border-emerald-100 bg-emerald-50 p-5">
                            <div class="flex items-start gap-3">
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-sm font-semibold text-emerald-700 ring-1 ring-emerald-100">3</div>
                                <div>
                                    <p class="text-lg font-semibold text-slate-950">Transfer tercatat</p>
                                    <p class="mt-1 text-sm leading-6 text-slate-600">Riwayat saldo tetap bisa dilacak dengan rapi.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
