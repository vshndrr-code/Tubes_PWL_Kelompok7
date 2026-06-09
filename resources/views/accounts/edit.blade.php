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

    .account-type-radio:checked + .account-type-card .selected-dot {
        background-color: white;
        opacity: 1;
        transform: scale(1);
    }

    .account-type-radio:checked + .account-type-card .selected-ring {
        background-color: rgb(16 185 129);
        --tw-ring-color: rgb(16 185 129);
        box-shadow: 0 0 0 1px rgb(16 185 129);
    }

    .account-type-radio:focus-visible + .account-type-card {
        outline: 2px solid rgb(148 163 184);
        outline-offset: 2px;
    }
</style>
@endpush

<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-950">Edit Akun</h2>
            <p class="mt-1 text-sm text-slate-500">Perbarui detail akun tanpa mengubah riwayat transaksi.</p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-[#f6f7f9] py-6 text-slate-900 sm:py-10">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="mb-7 flex flex-col gap-4 border-b border-slate-200 pb-6 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Kelola Dompet</p>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Edit Akun</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                        Sesuaikan nama, tipe, dan saldo akun yang sudah terdaftar.
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

            @php
                $types = [
                    'cash' => [
                        'label' => 'Cash',
                        'desc' => 'Uang tunai harian',
                        'color' => 'peer-checked:border-emerald-300 peer-checked:bg-emerald-50 peer-checked:ring-emerald-100',
                        'icon' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
                        'dot' => 'bg-emerald-600',
                        'svg' => '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12v5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-5"/><path d="M3 7h18"/><path d="M7 7v10"/><path d="M17 7v10"/><circle cx="12" cy="13" r="2"/></svg>',
                    ],
                    'bank' => [
                        'label' => 'Bank',
                        'desc' => 'Rekening tabungan',
                        'color' => 'peer-checked:border-sky-300 peer-checked:bg-sky-50 peer-checked:ring-sky-100',
                        'icon' => 'bg-sky-50 text-sky-700 ring-sky-100',
                        'dot' => 'bg-sky-600',
                        'svg' => '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10l9-6 9 6"/><path d="M5 10v10h14V10"/><path d="M10 14h4"/><path d="M7 20v-6"/><path d="M17 20v-6"/></svg>',
                    ],
                    'credit' => [
                        'label' => 'Kartu Kredit',
                        'desc' => 'Limit dan cicilan',
                        'color' => 'peer-checked:border-rose-300 peer-checked:bg-rose-50 peer-checked:ring-rose-100',
                        'icon' => 'bg-rose-50 text-rose-700 ring-rose-100',
                        'dot' => 'bg-rose-600',
                        'svg' => '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="3"/><path d="M2 11h20"/><path d="M6 16h2"/></svg>',
                    ],
                    'other' => [
                        'label' => 'E-wallet',
                        'desc' => 'Dompet digital',
                        'color' => 'peer-checked:border-amber-300 peer-checked:bg-amber-50 peer-checked:ring-amber-100',
                        'icon' => 'bg-amber-50 text-amber-700 ring-amber-100',
                        'dot' => 'bg-amber-600',
                        'svg' => '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M7 2h10a3 3 0 0 1 3 3v14a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V5a3 3 0 0 1 3-3Z"/><path d="M12 18h.01"/></svg>',
                    ],
                ];

                $selectedType = old('type', $account->type);
                $selectedStyle = $types[$selectedType] ?? $types['other'];
            @endphp

            <div class="grid gap-5 lg:grid-cols-[1fr_0.64fr]">
                <div class="ui-reveal overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4 sm:px-6">
                        <h3 class="text-lg font-semibold text-slate-950">Detail Akun</h3>
                        <p class="mt-1 text-sm text-slate-500">Perbarui informasi akun tanpa mengubah struktur transaksi.</p>
                    </div>

                    <form action="{{ route('accounts.update', $account) }}" method="POST" class="space-y-6 p-5 sm:p-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">Nama Akun</label>
                            <input id="name" name="name" value="{{ old('name', $account->name) }}" required
                                class="h-11 w-full rounded-lg border border-slate-200 bg-slate-50 px-3.5 text-sm font-medium text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100" />
                            @error('name')
                                <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <p class="mb-3 block text-sm font-semibold text-slate-700">Tipe Akun</p>

                            <div class="grid gap-3 sm:grid-cols-2">
                                @foreach ($types as $value => $type)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="type" value="{{ $value }}" class="account-type-radio peer sr-only"
                                            @checked(old('type', $account->type) === $value)>

                                        <div class="account-type-card ui-card rounded-lg border border-slate-200 bg-white p-4 shadow-sm ring-1 ring-transparent {{ $type['color'] }}">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="flex min-w-0 gap-3">
                                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg ring-1 {{ $type['icon'] }}">
                                                        {!! $type['svg'] !!}
                                                    </div>

                                                    <div class="min-w-0">
                                                        <p class="font-semibold text-slate-950">{{ $type['label'] }}</p>
                                                        <p class="mt-1 text-xs font-medium leading-5 text-slate-500">{{ $type['desc'] }}</p>
                                                    </div>
                                                </div>

                                                <span class="selected-ring mt-1 flex h-4 w-4 shrink-0 items-center justify-center rounded-full bg-white ring-1 ring-slate-300 transition">
                                                    <span class="selected-dot h-1.5 w-1.5 scale-50 rounded-full opacity-0 transition {{ $type['dot'] }}"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            @error('type')
                                <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="balance" class="mb-2 block text-sm font-semibold text-slate-700">Saldo Akun</label>
                            <div class="flex h-11 overflow-hidden rounded-lg border border-slate-200 bg-slate-50 transition focus-within:border-slate-400 focus-within:bg-white focus-within:ring-2 focus-within:ring-slate-100">
                                <span class="flex items-center border-r border-slate-200 px-3.5 text-sm font-semibold text-slate-500">Rp</span>
                                <input id="balance" name="balance" type="number" step="0.01" min="0"
                                    value="{{ old('balance', $account->balance) }}" required
                                    class="w-full border-0 bg-transparent px-3.5 text-sm font-medium text-slate-900 outline-none focus:ring-0" />
                            </div>
                            @error('balance')
                                <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">
                            <a href="{{ route('accounts.index') }}"
                                class="ui-button inline-flex h-11 items-center justify-center rounded-lg border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-700 shadow-sm hover:border-slate-300 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300">
                                Batal
                            </a>

                            <button type="submit"
                                class="ui-button inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-slate-950 px-5 text-sm font-semibold text-white shadow-sm shadow-slate-900/10 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 6L9 17l-5-5" />
                                </svg>
                                Perbarui Akun
                            </button>
                        </div>
                    </form>
                </div>

                <aside class="ui-reveal rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-6" style="animation-delay: .06s">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Akun Saat Ini</p>

                    <div class="mt-5 rounded-lg bg-slate-950 p-5 text-white shadow-sm shadow-slate-900/10">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <p class="text-sm text-slate-400">Nama akun</p>
                                <p class="mt-2 break-words text-2xl font-bold">{{ $account->name }}</p>
                            </div>

                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg ring-1 {{ $selectedStyle['icon'] }}">
                                {!! $selectedStyle['svg'] !!}
                            </div>
                        </div>

                        <div class="mt-8 rounded-lg border border-white/10 bg-white/[0.06] p-4">
                            <p class="text-sm text-slate-400">Saldo saat ini</p>
                            <p class="mt-1 text-3xl font-bold">
                                Rp{{ number_format($account->balance, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 grid gap-3">
                        <div class="ui-card rounded-lg border border-slate-200 bg-slate-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Tipe</p>
                            <p class="mt-2 font-semibold text-slate-950">
                                {{ $types[$account->type]['label'] ?? ucfirst($account->type) }}
                            </p>
                        </div>

                        <div class="ui-card rounded-lg border border-slate-200 bg-slate-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Terakhir diperbarui</p>
                            <p class="mt-2 font-semibold text-slate-950">
                                {{ optional($account->updated_at)->format('d M Y') ?? '-' }}
                            </p>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
