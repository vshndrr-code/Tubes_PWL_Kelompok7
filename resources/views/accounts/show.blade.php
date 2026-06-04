@extends('layouts.app')

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

@section('content')
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
                'icon' => 'bg-sky-50 text-sky-700 ring-sky-100',
                'badge' => 'bg-sky-50 text-sky-700 ring-sky-100',
                'amount' => 'text-sky-700',
            ],
            'cash' => [
                'line' => 'from-emerald-500 to-teal-400',
                'icon' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
                'badge' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
                'amount' => 'text-emerald-700',
            ],
            'credit' => [
                'line' => 'from-rose-500 to-red-400',
                'icon' => 'bg-rose-50 text-rose-700 ring-rose-100',
                'badge' => 'bg-rose-50 text-rose-700 ring-rose-100',
                'amount' => 'text-rose-700',
            ],
            'other' => [
                'line' => 'from-amber-500 to-orange-400',
                'icon' => 'bg-amber-50 text-amber-700 ring-amber-100',
                'badge' => 'bg-amber-50 text-amber-700 ring-amber-100',
                'amount' => 'text-amber-700',
            ],
        ];

        $type = $account->type ?? 'other';
        $style = $typeStyles[$type] ?? $typeStyles['other'];
        $balance = (float) $account->balance;
        $isArchived = !empty($account->archived_at);

        if (method_exists($account, 'transactions')) {
            $recentTransactions = $account
                ->transactions()
                ->with(['category'])
                ->latest('transaction_date')
                ->take(5)
                ->get();
            $transactionCount = $account->transactions()->count();
            $incomeTotal = $account->transactions()->where('type', 'income')->sum('amount');
            $expenseTotal = $account->transactions()->where('type', 'expense')->sum('amount');
        } else {
            $recentTransactions = collect();
            $transactionCount = 0;
            $incomeTotal = 0;
            $expenseTotal = 0;
        }
    @endphp

    <div class="min-h-screen bg-[#f6f7f9] py-6 text-slate-900 sm:py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-7 flex flex-col gap-4 border-b border-slate-200 pb-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Detail Dompet</p>
                    <h1 class="mt-2 break-words text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                        {{ $account->name }}</h1>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @if ($account->is_pinned ?? false)
                            <span
                                class="inline-flex rounded-md bg-slate-950 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] text-white">
                                Akun utama
                            </span>
                        @endif

                        @if ($isArchived)
                            <span
                                class="inline-flex rounded-md bg-slate-100 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-600 ring-1 ring-slate-200">
                                Diarsipkan
                            </span>
                        @endif
                    </div>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">
                        {{ $isArchived ? 'Akun ini sedang berada di arsip dan tidak muncul di daftar aktif.' : 'Pantau saldo, ringkasan transaksi, dan aktivitas terbaru akun ini.' }}
                    </p>
                </div>

                @unless ($isArchived)
                    <a href="{{ route('transactions.create', ['account_id' => $account->id]) }}"
                        class="ui-button inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 text-sm font-semibold text-white shadow-sm shadow-emerald-700/15 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-[#f6f7f9]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14" />
                            <path d="M5 12h14" />
                        </svg>
                        Tambah Transaksi
                    </a>
                @endunless
            </div>

            <div class="grid gap-5 lg:grid-cols-[1.05fr_0.95fr]">
                <section
                    class="ui-reveal relative overflow-hidden rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="absolute inset-y-0 left-0 w-1 bg-gradient-to-b {{ $style['line'] }}"></div>

                    <div class="relative">
                        <div class="flex items-start justify-between gap-5">
                            <div class="flex min-w-0 items-start gap-4">
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg ring-1 {{ $style['icon'] }}">
                                    {!! $typeIcons[$type] ?? $typeIcons['other'] !!}
                                </div>

                                <div class="min-w-0">
                                    <span
                                        class="inline-flex rounded-md px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] ring-1 {{ $style['badge'] }}">
                                        {{ $typeLabels[$type] ?? ucfirst($type) }}
                                    </span>
                                    @if ($account->is_pinned ?? false)
                                        <span
                                            class="ml-2 inline-flex rounded-md bg-slate-950 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] text-white">
                                            Utama
                                        </span>
                                    @endif
                                    <h2 class="mt-3 break-words text-2xl font-semibold text-slate-950">
                                        {{ $account->name }}
                                    </h2>
                                    <p class="mt-1 text-sm font-medium text-slate-500">
                                        Dibuat {{ optional($account->created_at)->format('d M Y') ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-9 rounded-lg bg-slate-50 p-5 ring-1 ring-slate-200">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Saldo Saat Ini</p>
                            <p
                                class="mt-3 text-4xl font-bold tracking-tight {{ $balance < 0 ? 'text-red-600' : $style['amount'] }} sm:text-5xl">
                                {{ $balance < 0 ? '-Rp' : 'Rp' }}{{ number_format(abs($balance), 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="mt-4 grid gap-3 sm:grid-cols-3">
                            <div class="ui-card rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                                <p class="text-xs font-semibold text-slate-500">Transaksi</p>
                                <p class="mt-2 text-2xl font-bold text-slate-950">{{ $transactionCount }}</p>
                            </div>

                            <div class="ui-card rounded-lg border border-emerald-100 bg-emerald-50 p-4 shadow-sm">
                                <p class="text-xs font-semibold text-emerald-600">Pemasukan</p>
                                <p class="mt-2 text-lg font-bold text-emerald-700">
                                    Rp{{ number_format($incomeTotal, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="ui-card rounded-lg border border-rose-100 bg-rose-50 p-4 shadow-sm">
                                <p class="text-xs font-semibold text-rose-600">Pengeluaran</p>
                                <p class="mt-2 text-lg font-bold text-rose-700">
                                    Rp{{ number_format($expenseTotal, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="ui-reveal rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-6"
                    style="animation-delay: .05s">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Transaksi Terbaru
                            </p>
                            <h3 class="mt-2 text-xl font-semibold text-slate-950">Aktivitas akun</h3>
                        </div>

                        <a href="{{ route('transactions.index', ['account_id' => $account->id]) }}"
                            class="rounded-md px-2 py-1 text-sm font-semibold text-sky-700 transition hover:bg-sky-50 hover:text-sky-800">
                            Lihat semua
                        </a>
                    </div>

                    <div class="mt-5 space-y-3">
                        @forelse ($recentTransactions as $transaction)
                            <div class="ui-card rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="min-w-0">
                                        <p class="truncate font-semibold text-slate-950">{{ $transaction->title }}</p>
                                        <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                                            <span>{{ optional($transaction->category)->name ?? 'Tanpa kategori' }}</span>
                                            <span class="text-slate-300">/</span>
                                            <span>{{ optional($transaction->transaction_date)->format('d M Y') ?? '-' }}</span>
                                        </div>
                                    </div>

                                    <p
                                        class="shrink-0 text-sm font-bold {{ $transaction->type === 'income' ? 'text-emerald-600' : 'text-red-600' }}">
                                        {{ $transaction->type === 'income' ? '+Rp' : '-Rp' }}{{ number_format($transaction->amount, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-10 text-center">
                                <div
                                    class="mx-auto flex h-14 w-14 items-center justify-center rounded-lg bg-white text-slate-400 shadow-sm ring-1 ring-slate-200">
                                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 6h16" />
                                        <path d="M4 12h10" />
                                        <path d="M4 18h7" />
                                    </svg>
                                </div>
                                <p class="mt-4 font-semibold text-slate-800">Belum ada transaksi</p>
                                <p class="mt-1 text-sm text-slate-500">Mulai catat aktivitas pertama untuk akun ini.</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>

            <section class="ui-reveal mt-5 rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-6"
                style="animation-delay: .1s">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Aksi Cepat</p>
                        <h3 class="mt-2 text-xl font-semibold text-slate-950">Kelola akun ini</h3>
                    </div>

                    <div class="flex flex-col gap-2 sm:flex-row">
                        @unless ($isArchived)
                            <form action="{{ route('accounts.pin', $account) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="ui-button inline-flex h-11 w-full items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm hover:border-slate-300 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300 sm:w-auto">
                                    <svg class="h-4 w-4 {{ $account->is_pinned ?? false ? 'fill-slate-950 text-slate-950' : '' }}"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 17.3 18.2 21l-1.6-7.1L22 9.1l-7.2-.6L12 2 9.2 8.5 2 9.1l5.4 4.8L5.8 21z" />
                                    </svg>
                                    {{ $account->is_pinned ?? false ? 'Lepas Pin' : 'Pin Utama' }}
                                </button>
                            </form>

                            <a href="{{ route('accounts.transfer') }}"
                                class="ui-button inline-flex h-11 items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm hover:border-slate-300 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 7h16" />
                                    <path d="M4 12h10" />
                                    <path d="M16 17l4-4-4-4" />
                                </svg>
                                Transfer
                            </a>
                        @endunless

                        <a href="{{ route('accounts.edit', $account) }}"
                            class="ui-button inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-slate-950 px-4 text-sm font-semibold text-white shadow-sm shadow-slate-900/10 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5" />
                                <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z" />
                            </svg>
                            Edit Detail
                        </a>

                        @if ($isArchived)
                            <form action="{{ route('accounts.restore', $account) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="ui-button inline-flex h-11 w-full items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 text-sm font-semibold text-white shadow-sm shadow-emerald-700/15 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 sm:w-auto">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 12a9 9 0 1 0 3-6.7" />
                                        <path d="M3 4v6h6" />
                                    </svg>
                                    Pulihkan
                                </button>
                            </form>
                        @else
                            <form action="{{ route('accounts.archive', $account) }}" method="POST"
                                onsubmit="return confirm('Arsipkan akun ini? Akun tidak akan muncul di daftar aktif.')">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="ui-button inline-flex h-11 w-full items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm hover:border-slate-300 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300 sm:w-auto">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 8v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8" />
                                        <path d="M10 12h4" />
                                        <path d="M3 8l2-5h14l2 5" />
                                    </svg>
                                    Arsipkan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
