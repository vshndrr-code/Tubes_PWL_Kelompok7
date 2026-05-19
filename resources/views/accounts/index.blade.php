@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
        <div class="mx-auto max-w-6xl px-4 py-8">
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
                        'line' => 'from-blue-600 to-cyan-400',
                        'soft' => 'bg-blue-50 text-blue-700 ring-blue-200',
                        'icon' => 'bg-blue-100 text-blue-700',
                        'amount' => 'text-blue-700',
                    ],
                    'cash' => [
                        'line' => 'from-emerald-500 to-teal-400',
                        'soft' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                        'icon' => 'bg-emerald-100 text-emerald-700',
                        'amount' => 'text-emerald-700',
                    ],
                    'credit' => [
                        'line' => 'from-rose-500 to-red-400',
                        'soft' => 'bg-rose-50 text-rose-700 ring-rose-200',
                        'icon' => 'bg-rose-100 text-rose-700',
                        'amount' => 'text-rose-700',
                    ],
                    'other' => [
                        'line' => 'from-violet-500 to-fuchsia-400',
                        'soft' => 'bg-violet-50 text-violet-700 ring-violet-200',
                        'icon' => 'bg-violet-100 text-violet-700',
                        'amount' => 'text-violet-700',
                    ],
                ];

                $accountItems =
                    $accounts instanceof \Illuminate\Pagination\AbstractPaginator
                        ? $accounts->getCollection()
                        : collect($accounts);

                $totalBalance = $accountItems->sum('balance');
                $activeCount = $accountItems->count();
                $topAccount = $accountItems->sortByDesc('balance')->first();
                $latestUpdate = optional($accountItems->sortByDesc('updated_at')->first())->updated_at;
            @endphp

            <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.28em] text-slate-400">Wallet Center</p>
                    <h1 class="mt-2 text-4xl font-extrabold tracking-tight text-slate-950">Accounts</h1>
                    <p class="mt-2 text-slate-500">Kelola semua akun dan dompet keuangan Anda.</p>
                </div>

                <div class="flex w-full flex-col gap-3 sm:flex-row lg:w-auto">
                    <a href="{{ route('accounts.transfer') }}"
                        class="inline-flex h-12 items-center justify-center gap-2 rounded-2xl bg-slate-950 px-5 text-sm font-bold text-white shadow-lg shadow-slate-900/15 transition hover:bg-slate-800">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 7h16" />
                            <path d="M4 12h10" />
                            <path d="M16 17l4-4-4-4" />
                        </svg>
                        Transfer
                    </a>

                    <a href="{{ route('accounts.create') }}"
                        class="inline-flex h-12 items-center justify-center gap-2 rounded-2xl bg-emerald-500 px-5 text-sm font-bold text-white shadow-lg shadow-emerald-600/20 transition hover:bg-emerald-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Akun
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-3xl border border-emerald-200 bg-emerald-50 px-6 py-4 text-sm font-semibold text-emerald-900 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6 grid gap-4 lg:grid-cols-[1.35fr_0.65fr]">
                <div class="relative overflow-hidden rounded-[32px] bg-slate-950 p-7 text-white shadow-2xl shadow-slate-900/20">
                    <div class="absolute -right-16 -top-16 h-48 w-48 rounded-full bg-blue-500/25 blur-2xl"></div>
                    <div class="absolute bottom-0 right-0 h-32 w-64 bg-gradient-to-l from-emerald-400/20 to-transparent"></div>

                    <div class="relative">
                        <p class="text-xs font-bold uppercase tracking-[0.28em] text-slate-400">Total Balance</p>
                        <p class="mt-4 text-4xl font-extrabold tracking-tight sm:text-5xl">
                            Rp{{ number_format($totalBalance, 0, ',', '.') }}
                        </p>

                        <div class="mt-8 grid gap-3 sm:grid-cols-3">
                            <div class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/10">
                                <p class="text-xs text-slate-400">Akun aktif</p>
                                <p class="mt-2 text-2xl font-bold">{{ $activeCount }}</p>
                            </div>

                            <div class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/10">
                                <p class="text-xs text-slate-400">Wallet terbesar</p>
                                <p class="mt-2 truncate text-base font-bold">
                                    {{ $topAccount?->name ?? '-' }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/10">
                                <p class="text-xs text-slate-400">Update terakhir</p>
                                <p class="mt-2 text-base font-bold">
                                    {{ optional($latestUpdate)->format('d M Y') ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
                    <div class="rounded-[28px] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5">
                        <p class="text-xs font-bold uppercase tracking-[0.24em] text-slate-400">Portfolio</p>
                        <p class="mt-3 text-3xl font-extrabold text-slate-950">{{ $activeCount }}</p>
                        <p class="mt-1 text-sm text-slate-500">Dompet tersedia.</p>
                    </div>

                    <div class="rounded-[28px] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5">
                        <p class="text-xs font-bold uppercase tracking-[0.24em] text-slate-400">Average</p>
                        <p class="mt-3 text-3xl font-extrabold text-slate-950">
                            Rp{{ number_format($activeCount > 0 ? $totalBalance / $activeCount : 0, 0, ',', '.') }}
                        </p>
                        <p class="mt-1 text-sm text-slate-500">Rata-rata saldo.</p>
                    </div>
                </div>
            </div>

            <div class="mb-8 grid grid-cols-2 gap-3 lg:grid-cols-4">
                @foreach ($typeLabels as $type => $label)
                    @php
                        $style = $typeStyles[$type] ?? $typeStyles['other'];
                        $typeCount = $accountItems->where('type', $type)->count();
                        $typeTotal = $accountItems->where('type', $type)->sum('balance');
                    @endphp

                    <div class="rounded-3xl border border-white/70 bg-white/80 p-4 shadow-sm ring-1 ring-slate-200/50">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-2xl {{ $style['icon'] }}">
                                {!! $typeIcons[$type] ?? $typeIcons['other'] !!}
                            </div>
                            <span class="text-xs font-bold text-slate-400">{{ $typeCount }} akun</span>
                        </div>
                        <p class="mt-4 text-sm font-bold text-slate-900">{{ $label }}</p>
                        <p class="mt-1 truncate text-sm font-semibold {{ $typeTotal < 0 ? 'text-red-600' : 'text-slate-500' }}">
                            {{ $typeTotal < 0 ? '-Rp' : 'Rp' }}{{ number_format(abs($typeTotal), 0, ',', '.') }}
                        </p>
                    </div>
                @endforeach
            </div>

            @if ($accounts->isEmpty())
                <div class="rounded-[32px] border border-dashed border-slate-300 bg-white/80 p-12 text-center shadow-sm">
                    <div class="mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-3xl bg-slate-100 text-slate-400">
                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                d="M3 10.5V19a2 2 0 002 2h14a2 2 0 002-2v-8.5M5 10.5L12 4l7 6.5M5 10.5h14" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">Belum ada akun</h3>
                    <p class="mt-2 text-slate-500">Tambahkan akun pertama untuk mulai mengelola saldo.</p>
                    <a href="{{ route('accounts.create') }}"
                        class="mt-6 inline-flex h-12 items-center justify-center gap-2 rounded-2xl bg-emerald-500 px-6 text-sm font-bold text-white shadow-lg shadow-emerald-600/20 transition hover:bg-emerald-600">
                        Tambah Akun Pertamamu
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($accounts as $account)
                        @php
                            $typeIcon = $typeIcons[$account->type] ?? $typeIcons['other'];
                            $style = $typeStyles[$account->type] ?? $typeStyles['other'];
                        @endphp

                        <article class="group relative overflow-hidden rounded-[30px] border border-white/70 bg-white/90 p-4 shadow-xl shadow-slate-900/5 ring-1 ring-slate-200/70 transition-all duration-200 hover:border-slate-300 hover:bg-white hover:shadow-2xl hover:shadow-slate-900/10 sm:p-5">
    <a href="{{ route('accounts.show', $account) }}"
        class="absolute inset-0 z-0 rounded-[30px]"
        aria-label="Lihat detail akun {{ $account->name }}"></a>
                            <div class="absolute inset-y-0 left-0 w-1.5 bg-gradient-to-b {{ $style['line'] }}"></div>
                            <div class="absolute -right-12 -top-12 h-32 w-32 rounded-full bg-gradient-to-br {{ $style['line'] }} opacity-10"></div>

                            <div class="pointer-events-none relative z-10 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                <div class="flex min-w-0 flex-1 items-start gap-4 md:items-center">
                                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl {{ $style['icon'] }} ring-8 ring-slate-50">
                                        {!! $typeIcon !!}
                                    </div>

                                    <div class="min-w-0">
                                        <h3 class="break-words text-lg font-extrabold text-slate-950">
                                            {{ $account->name }}
                                        </h3>

                                        <div class="mt-2 flex flex-wrap items-center gap-2">
                                            <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] ring-1 {{ $style['soft'] }}">
                                                {{ $typeLabels[$account->type] ?? ucfirst($account->type) }}
                                            </span>

                                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">
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
                                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">Saldo</p>
                                        <p class="mt-1 whitespace-nowrap text-xl font-extrabold {{ $account->balance < 0 ? 'text-red-600' : $style['amount'] }} sm:text-2xl">
                                            {{ $account->balance < 0 ? '-Rp' : 'Rp' }}{{ number_format(abs($account->balance), 0, ',', '.') }}
                                        </p>
                                    </div>

                                    <div class="pointer-events-auto mt-0 flex shrink-0 justify-end gap-2 md:mt-3">
                                        <a href="{{ route('accounts.edit', $account) }}"
                                            class="inline-flex h-10 items-center justify-center gap-2 rounded-full bg-white px-3 text-sm font-bold text-amber-600 shadow-sm ring-1 ring-slate-200 transition hover:bg-amber-50">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <span class="hidden sm:inline">Edit</span>
                                        </a>

                                        <form action="{{ route('accounts.destroy', $account) }}" method="POST"
                                            onsubmit="return confirm('Hapus akun ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex h-10 items-center justify-center gap-2 rounded-full bg-white px-3 text-sm font-bold text-red-600 shadow-sm ring-1 ring-red-100 transition hover:bg-red-50">
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
                </div>

                @if ($accounts instanceof \Illuminate\Pagination\AbstractPaginator)
                    <div class="mt-8">
                        {{ $accounts->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection