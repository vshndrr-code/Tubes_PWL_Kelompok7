@extends('layouts.app')

@push('head')
<style>
    [x-cloak] { display: none !important; }

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

@section('content')
    <div class="min-h-screen bg-[#f6f7f9] text-slate-900">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-10 lg:px-8">
            @php
                $typeLabels = [
                    'bank' => 'Bank',
                    'cash' => 'Cash',
                    'credit' => 'Kartu Kredit',
                    'other' => 'E-wallet',
                ];

                $typeIcons = [
                    'bank' =>
                        '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10l9-6 9 6"/><path d="M5 10v10h14V10"/><path d="M10 14h4"/><path d="M7 20v-6"/><path d="M17 20v-6"/></svg>',
                    'cash' =>
                        '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12v5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-5"/><path d="M3 7h18"/><path d="M7 7v10"/><path d="M17 7v10"/><circle cx="12" cy="13" r="2"/></svg>',
                    'credit' =>
                        '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="3"/><path d="M2 11h20"/><path d="M6 16h2"/></svg>',
                    'other' =>
                        '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M7 2h10a3 3 0 0 1 3 3v14a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V5a3 3 0 0 1 3-3Z"/><path d="M12 18h.01"/></svg>',
                ];

                $typeStyles = [
                    'bank' => [
                        'line' => 'from-sky-500 to-cyan-400',
                        'soft' => 'bg-sky-50 text-sky-700 ring-sky-100',
                        'icon' => 'bg-sky-50 text-sky-700 ring-sky-100',
                        'amount' => 'text-sky-700',
                    ],
                    'cash' => [
                        'line' => 'from-emerald-500 to-teal-400',
                        'soft' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
                        'icon' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
                        'amount' => 'text-emerald-700',
                    ],
                    'credit' => [
                        'line' => 'from-rose-500 to-red-400',
                        'soft' => 'bg-rose-50 text-rose-700 ring-rose-100',
                        'icon' => 'bg-rose-50 text-rose-700 ring-rose-100',
                        'amount' => 'text-rose-700',
                    ],
                    'other' => [
                        'line' => 'from-amber-500 to-orange-400',
                        'soft' => 'bg-amber-50 text-amber-700 ring-amber-100',
                        'icon' => 'bg-amber-50 text-amber-700 ring-amber-100',
                        'amount' => 'text-amber-700',
                    ],
                ];

                $accountItems =
                    $accounts instanceof \Illuminate\Pagination\AbstractPaginator
                        ? $accounts->getCollection()
                        : collect($accounts);

                $showArchived = request()->boolean('archived');
                $totalBalance = $accountItems->sum('balance');
                $activeCount = $accountItems->count();
                $topAccount = $accountItems->sortByDesc('balance')->first();
                $latestUpdate = optional($accountItems->sortByDesc('updated_at')->first())->updated_at;
                $typeCounts = $accountItems->countBy('type');
            @endphp

            <div x-data="{
                filter: 'all',
                search: '',
                accounts: @js($accountItems->map(fn($item) => ['type' => $item->type, 'name' => $item->name])),
                counts: @js($typeCounts),
                matches(type, name = '') {
                    const matchesType = this.filter === 'all' || this.filter === type;
                    const matchesSearch = this.search === '' || name.toLowerCase().includes(this.search.toLowerCase());
                    return matchesType && matchesSearch;
                },
                hasMatches() {
                    return this.accounts.some(acc => this.matches(acc.type, acc.name));
                }
            }">
                <div class="mb-7 flex flex-col gap-4 border-b border-slate-200 pb-6 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Pusat Dompet</p>
                        <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                            {{ $showArchived ? 'Arsip Akun' : 'Akun Keuangan' }}
                        </h1>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                            {{ $showArchived ? 'Lihat akun yang sudah disimpan di arsip.' : 'Pantau saldo, pindahkan dana, dan kelola semua akun dari satu tempat.' }}
                        </p>
                    </div>

                    <div class="flex w-full flex-col gap-2 sm:flex-row lg:w-auto">
                        <a href="{{ $showArchived ? route('accounts.index') : route('accounts.index', ['archived' => 1]) }}"
                            class="ui-button inline-flex h-11 items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm hover:border-slate-300 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300 focus:ring-offset-2 focus:ring-offset-[#f6f7f9]">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 8v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8" />
                                <path d="M10 12h4" />
                                <path d="M3 8l2-5h14l2 5" />
                            </svg>
                            {{ $showArchived ? 'Akun Aktif' : 'Arsip' }}
                        </a>

                        <a href="{{ route('accounts.transfer') }}"
                            class="ui-button inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-slate-950 px-4 text-sm font-semibold text-white shadow-sm shadow-slate-900/10 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 focus:ring-offset-[#f6f7f9]">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 7h16" />
                                <path d="M4 12h10" />
                                <path d="M16 17l4-4-4-4" />
                            </svg>
                            Transfer
                        </a>

                        <a href="{{ route('accounts.create') }}"
                            class="ui-button inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 text-sm font-semibold text-white shadow-sm shadow-emerald-700/15 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-[#f6f7f9]">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Akun
                        </a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mb-6 rounded-lg border border-emerald-200 bg-white px-4 py-3 text-sm font-medium text-emerald-800 shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mb-5 grid gap-4 lg:grid-cols-[1.45fr_0.55fr]">
                    <div class="ui-reveal overflow-hidden rounded-lg bg-slate-950 p-6 text-white shadow-lg shadow-slate-900/10">
                        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Total Saldo</p>
                                <p class="mt-3 text-3xl font-bold tracking-tight sm:text-5xl">
                                    {{ $currencySymbol }}{{ number_format($totalBalance, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="rounded-lg border border-white/10 bg-white/[0.06] px-4 py-3">
                                <p class="text-xs text-slate-400">Update terakhir</p>
                                <p class="mt-1 text-sm font-semibold text-white">
                                    {{ optional($latestUpdate)->format('d M Y') ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-7 grid gap-3 sm:grid-cols-3">
                            <div class="rounded-lg border border-white/10 bg-white/[0.06] p-4">
                                <p class="text-xs text-slate-400">{{ $showArchived ? 'Akun arsip' : 'Akun aktif' }}</p>
                                <p class="mt-2 text-2xl font-bold">{{ $activeCount }}</p>
                            </div>

                            <div class="rounded-lg border border-white/10 bg-white/[0.06] p-4">
                                <p class="text-xs text-slate-400">Wallet terbesar</p>
                                <p class="mt-2 truncate text-base font-semibold">
                                    {{ $topAccount?->name ?? '-' }}
                                </p>
                            </div>

                            <div class="rounded-lg border border-white/10 bg-white/[0.06] p-4">
                                <p class="text-xs text-slate-400">Rata-rata saldo</p>
                                <p class="mt-2 truncate text-base font-semibold">
                                    {{ $currencySymbol }}{{ number_format($activeCount > 0 ? $totalBalance / $activeCount : 0, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
                        <div class="ui-card rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Portofolio</p>
                            <p class="mt-3 text-3xl font-bold text-slate-950">{{ $activeCount }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $showArchived ? 'Tersimpan di arsip.' : 'Akun tersedia.' }}</p>
                        </div>

                        <div class="ui-card rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Rata-rata</p>
                            <p class="mt-3 text-3xl font-bold text-slate-950">
                                {{ $currencySymbol }}{{ number_format($activeCount > 0 ? $totalBalance / $activeCount : 0, 0, ',', '.') }}
                            </p>
                            <p class="mt-1 text-sm text-slate-500">{{ $showArchived ? 'Per akun arsip.' : 'Per akun aktif.' }}</p>
                        </div>
                    </div>
                </div>

                <div class="mb-4 rounded-lg border border-slate-200 bg-white p-3 shadow-sm">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <div class="relative w-full lg:max-w-md">
                            <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                            </svg>
                            <input
                                x-model="search"
                                type="search"
                                placeholder="Cari akun..."
                                class="h-10 w-full rounded-lg border border-slate-200 bg-slate-50 px-10 text-sm text-slate-700 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100"
                            />
                        </div>

                        <div class="flex flex-wrap gap-1 rounded-lg bg-slate-100 p-1">
                            <button type="button"
                                @click="filter = 'all'"
                                :class="filter === 'all' ? 'bg-white text-slate-950 shadow-sm' : 'text-slate-600 hover:text-slate-950'"
                                class="rounded-md px-3 py-2 text-xs font-semibold transition">
                                Semua
                            </button>
                            @foreach ($typeLabels as $type => $label)
                                <button type="button"
                                    @click="filter = '{{ $type }}'"
                                    :class="filter === '{{ $type }}' ? 'bg-white text-slate-950 shadow-sm' : 'text-slate-600 hover:text-slate-950'"
                                    class="rounded-md px-3 py-2 text-xs font-semibold transition">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mb-7 grid grid-cols-2 gap-3 lg:grid-cols-4">
                    @foreach ($typeLabels as $type => $label)
                        @php
                            $style = $typeStyles[$type] ?? $typeStyles['other'];
                            $typeCount = $accountItems->where('type', $type)->count();
                            $typeTotal = $accountItems->where('type', $type)->sum('balance');
                        @endphp

                        <button type="button"
                            @click="filter = @js($type)"
                            :class="filter === @js($type) ? 'border-slate-900 bg-white shadow-md ring-1 ring-slate-900/5' : 'border-slate-200 bg-white hover:border-slate-300 hover:shadow-md'"
                            class="ui-card rounded-lg border p-4 text-left shadow-sm">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg ring-1 {{ $style['icon'] }}">
                                    {!! $typeIcons[$type] ?? $typeIcons['other'] !!}
                                </div>
                                <span class="text-xs font-semibold text-slate-500">{{ $typeCount }} akun</span>
                            </div>
                            <p class="mt-4 text-sm font-semibold text-slate-950">{{ $label }}</p>
                            <p class="mt-1 truncate text-sm font-medium {{ $typeTotal < 0 ? 'text-red-600' : 'text-slate-500' }}">
                                {{ $typeTotal < 0 ? '-' . $currencySymbol : $currencySymbol }}{{ number_format(abs($typeTotal), 0, ',', '.') }}
                            </p>
                        </button>
                    @endforeach
                </div>

                @if ($accountItems->isEmpty())
                    <div class="rounded-lg border border-dashed border-slate-300 bg-white p-10 text-center shadow-sm">
                        <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-lg bg-slate-100 text-slate-400">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                    d="M3 10.5V19a2 2 0 002 2h14a2 2 0 002-2v-8.5M5 10.5L12 4l7 6.5M5 10.5h14" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-950">Belum ada akun</h3>
                        <p class="mt-2 text-sm text-slate-500">
                            {{ $showArchived ? 'Belum ada akun yang diarsipkan.' : 'Tambahkan akun pertama untuk mulai mengelola saldo.' }}
                        </p>
                        @unless ($showArchived)
                            <a href="{{ route('accounts.create') }}"
                                class="ui-button mt-6 inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-emerald-600 px-5 text-sm font-semibold text-white shadow-sm shadow-emerald-700/15 hover:bg-emerald-700">
                                Tambah Akun Pertamamu
                            </a>
                        @endunless
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($accounts as $account)
                            @php
                                $typeIcon = $typeIcons[$account->type] ?? $typeIcons['other'];
                                $style = $typeStyles[$account->type] ?? $typeStyles['other'];
                                $isArchived = ! empty($account->archived_at);
                            @endphp

                            <article
                                x-show="matches(@js($account->type), @js($account->name))"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-1"
                                class="ui-card group relative overflow-hidden rounded-lg border border-slate-200 bg-white p-4 shadow-sm hover:border-slate-300 hover:shadow-md sm:p-5">
                                <a href="{{ route('accounts.show', $account) }}"
                                    class="absolute inset-0 z-0 rounded-lg"
                                    aria-label="Lihat detail akun {{ $account->name }}"></a>

                                <div class="absolute inset-y-0 left-0 w-1 bg-gradient-to-b {{ $style['line'] }}"></div>

                                <div class="pointer-events-none relative z-10 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                    <div class="flex min-w-0 flex-1 items-start gap-4 md:items-center">
                                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg ring-1 {{ $style['icon'] }}">
                                            {!! $typeIcon !!}
                                        </div>

                                        <div class="min-w-0">
                                            <h3 class="break-words text-lg font-semibold text-slate-950">
                                                {{ $account->name }}
                                            </h3>

                                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                                @if ($account->is_pinned ?? false)
                                                    <span class="inline-flex rounded-md bg-slate-950 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] text-white">
                                                        Utama
                                                    </span>
                                                @endif

                                                @if ($isArchived)
                                                    <span class="inline-flex rounded-md bg-slate-100 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-600 ring-1 ring-slate-200">
                                                        Arsip
                                                    </span>
                                                @endif

                                                <span class="inline-flex rounded-md px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] ring-1 {{ $style['soft'] }}">
                                                    {{ $typeLabels[$account->type] ?? ucfirst($account->type) }}
                                                </span>

                                                <span class="rounded-md bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                                    {{ $account->transactions_count ?? 0 }} transaksi
                                                </span>

                                                <span class="basis-full text-xs font-medium text-slate-400 sm:basis-auto">
                                                    Update {{ optional($account->updated_at)->format('d M Y') ?? '-' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between gap-4 border-t border-slate-100 pt-4 md:block md:border-t-0 md:pt-0 md:text-right">
                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Saldo</p>
                                            <p class="mt-1 whitespace-nowrap text-xl font-bold {{ $account->balance < 0 ? 'text-red-600' : $style['amount'] }} sm:text-2xl">
                                                {{ $account->balance < 0 ? '-' . $currencySymbol : $currencySymbol }}{{ number_format(abs($account->balance), 0, ',', '.') }}
                                            </p>
                                        </div>

                                        <div class="pointer-events-auto mt-0 flex shrink-0 justify-end gap-2 md:mt-3">
                                            @unless ($isArchived)
                                                <form action="{{ route('accounts.pin', $account) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="ui-button inline-flex h-9 items-center justify-center gap-2 rounded-md bg-white px-3 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-slate-200 hover:bg-slate-50 hover:ring-slate-300 focus:outline-none focus:ring-2 focus:ring-slate-300"
                                                        aria-label="{{ ($account->is_pinned ?? false) ? 'Lepas pin akun' : 'Pin akun utama' }}">
                                                        <svg class="h-4 w-4 {{ ($account->is_pinned ?? false) ? 'fill-slate-950 text-slate-950' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M12 17.3 18.2 21l-1.6-7.1L22 9.1l-7.2-.6L12 2 9.2 8.5 2 9.1l5.4 4.8L5.8 21z" />
                                                        </svg>
                                                        <span class="hidden sm:inline">{{ ($account->is_pinned ?? false) ? 'Unpin' : 'Pin' }}</span>
                                                    </button>
                                                </form>
                                            @endunless

                                            <a href="{{ route('accounts.edit', $account) }}"
                                                class="ui-button inline-flex h-9 items-center justify-center gap-2 rounded-md bg-white px-3 text-sm font-semibold text-amber-700 shadow-sm ring-1 ring-slate-200 hover:bg-amber-50 hover:ring-amber-100 focus:outline-none focus:ring-2 focus:ring-amber-300">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                <span class="hidden sm:inline">Edit</span>
                                            </a>

                                            @if ($isArchived)
                                                <form action="{{ route('accounts.restore', $account) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="ui-button inline-flex h-9 items-center justify-center gap-2 rounded-md bg-white px-3 text-sm font-semibold text-emerald-700 shadow-sm ring-1 ring-emerald-100 hover:bg-emerald-50 hover:ring-emerald-200 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M3 12a9 9 0 1 0 3-6.7" />
                                                            <path d="M3 4v6h6" />
                                                        </svg>
                                                        <span class="hidden sm:inline">Pulihkan</span>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('accounts.archive', $account) }}" method="POST"
                                                    onsubmit="return confirm('Arsipkan akun ini? Akun tidak akan muncul di daftar aktif.')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="ui-button inline-flex h-9 items-center justify-center gap-2 rounded-md bg-white px-3 text-sm font-semibold text-slate-600 shadow-sm ring-1 ring-slate-200 hover:bg-slate-50 hover:ring-slate-300 focus:outline-none focus:ring-2 focus:ring-slate-300">
                                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M21 8v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8" />
                                                            <path d="M10 12h4" />
                                                            <path d="M3 8l2-5h14l2 5" />
                                                        </svg>
                                                        <span class="hidden sm:inline">Arsip</span>
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('accounts.destroy', $account) }}" method="POST"
                                                onsubmit="return confirm('Hapus akun ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="ui-button inline-flex h-9 items-center justify-center gap-2 rounded-md bg-white px-3 text-sm font-semibold text-red-600 shadow-sm ring-1 ring-red-100 hover:bg-red-50 hover:ring-red-200 focus:outline-none focus:ring-2 focus:ring-red-200">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    <span class="hidden sm:inline">Hapus</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach

                        <div x-cloak x-show="!hasMatches()"
                            class="rounded-lg border border-dashed border-slate-300 bg-white p-10 text-center shadow-sm">
                            <h3 class="text-lg font-semibold text-slate-950">Tidak ada akun di kategori ini</h3>
                            <p class="mt-2 text-sm text-slate-500">Pilih kategori lain atau tambah akun baru.</p>
                        </div>
                    </div>

                    @if ($accounts instanceof \Illuminate\Pagination\AbstractPaginator)
                        <div class="mt-8">
                            {{ $accounts->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection
