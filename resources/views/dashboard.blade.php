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
    <div class="min-h-screen bg-[#f6f7f9] text-slate-900">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-10 lg:px-8">
            @php
                $accountItems =
                    isset($accounts) && $accounts instanceof \Illuminate\Pagination\AbstractPaginator
                        ? $accounts->getCollection()
                        : collect($accounts ?? []);

                $transactionItems =
                    isset($transactions) && $transactions instanceof \Illuminate\Pagination\AbstractPaginator
                        ? $transactions->getCollection()
                        : collect($transactions ?? []);

                $recentItems = collect($recentTransactions ?? $transactionItems)->take(6);
                $budgetItems = collect($budgetings ?? $budgets ?? []);

                $totalBalance = $totalBalance ?? $accountBalance ?? $accountItems->sum('balance');
                $monthlyIncome = $monthlyIncome ?? $currentMonthIncome ?? $transactionItems->where('type', 'income')->filter(fn($t) => $t->transaction_date && $t->transaction_date->isCurrentMonth())->sum('amount');
                $monthlyExpense = $monthlyExpense ?? $currentMonthExpense ?? $transactionItems->where('type', 'expense')->filter(fn($t) => $t->transaction_date && $t->transaction_date->isCurrentMonth())->sum('amount');
                $netCashflow = $monthlyIncome - $monthlyExpense;
                $accountCount = $accountCount ?? $accountItems->count();
                $transactionCount = $transactionCount ?? $transactionItems->count();
                $currentMonthBudgets = $budgetItems->filter(fn($b) => (int)$b->month === (int)now()->month && (int)$b->year === (int)now()->year);
                $budgetLimit = $currentMonthBudgets->sum('limit_amount');
                $budgetSpent = $currentMonthBudgets->sum('spent_amount');
                $budgetProgress = $budgetLimit > 0 ? min(($budgetSpent / $budgetLimit) * 100, 100) : 0;
                $latestTransaction = $recentItems->first();

                $transactionsIndexUrl = \Illuminate\Support\Facades\Route::has('transactions.index') ? route('transactions.index') : '#';
                $transactionsCreateUrl = \Illuminate\Support\Facades\Route::has('transactions.create') ? route('transactions.create') : '#';
                $accountsIndexUrl = \Illuminate\Support\Facades\Route::has('accounts.index') ? route('accounts.index') : '#';
                $accountsCreateUrl = \Illuminate\Support\Facades\Route::has('accounts.create') ? route('accounts.create') : '#';
                $budgetUrl = \Illuminate\Support\Facades\Route::has('budgetings.index') ? route('budgetings.index') : '#';
            @endphp

            <div class="mb-7 flex flex-col gap-4 border-b border-slate-200 pb-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Dashboard Keuangan</p>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Ringkasan Hari Ini</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                        Pantau saldo, arus kas bulanan, budget, dan aktivitas terbaru dalam satu tampilan.
                    </p>
                </div>

            </div>

            <div class="mb-5 grid gap-4 lg:grid-cols-[1.45fr_0.55fr]">
                <a href="{{ route('accounts.index') }}" class="ui-reveal block overflow-hidden rounded-lg bg-slate-950 p-6 text-white shadow-lg shadow-slate-900/10 hover:bg-slate-900 transition duration-150">
                    <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Total Saldo</p>
                            <p class="mt-3 text-3xl font-bold tracking-tight sm:text-5xl">
                                {{ $totalBalance < 0 ? '-' . $currencySymbol : $currencySymbol }}{{ number_format(abs($totalBalance), 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="rounded-lg border border-white/10 bg-white/[0.06] px-4 py-3">
                            <p class="text-xs text-slate-400">Update terakhir</p>
                            <p class="mt-1 text-sm font-semibold text-white">
                                {{ optional(optional($latestTransaction)->transaction_date)->format('d M Y') ?? now()->format('d M Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-7 grid gap-3 sm:grid-cols-3">
                        <div class="rounded-lg border border-white/10 bg-white/[0.06] p-4">
                            <p class="text-xs text-slate-400">Pemasukan bulan ini</p>
                            <p class="mt-2 text-lg font-bold text-emerald-300">
                                {{ $currencySymbol }}{{ number_format($monthlyIncome, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="rounded-lg border border-white/10 bg-white/[0.06] p-4">
                            <p class="text-xs text-slate-400">Pengeluaran bulan ini</p>
                            <p class="mt-2 text-lg font-bold text-rose-300">
                                {{ $currencySymbol }}{{ number_format($monthlyExpense, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="rounded-lg border border-white/10 bg-white/[0.06] p-4">
                            <p class="text-xs text-slate-400">Net cashflow</p>
                            <p class="mt-2 text-lg font-bold {{ $netCashflow < 0 ? 'text-rose-300' : 'text-emerald-300' }}">
                                {{ $netCashflow < 0 ? '-' . $currencySymbol : $currencySymbol }}{{ number_format(abs($netCashflow), 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </a>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
                    <a href="{{ route('accounts.index') }}" class="ui-card block rounded-lg border border-slate-200 bg-white p-5 shadow-sm hover:border-slate-300 hover:shadow-md transition">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Akun</p>
                        <p class="mt-3 text-3xl font-bold text-slate-950">{{ $accountCount }}</p>
                        <p class="mt-1 text-sm text-slate-500">Dompet dan rekening aktif.</p>
                    </a>

                    <a href="{{ route('transactions.index') }}" class="ui-card block rounded-lg border border-slate-200 bg-white p-5 shadow-sm hover:border-slate-300 hover:shadow-md transition">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Transaksi</p>
                        <p class="mt-3 text-3xl font-bold text-slate-950">{{ $transactionCount }}</p>
                        <p class="mt-1 text-sm text-slate-500">Aktivitas yang tercatat.</p>
                    </a>
                </div>
            </div>

            <div class="mb-5 grid gap-4 md:grid-cols-2">
                <a href="{{ route('transactions.index') }}" class="ui-card block rounded-lg border border-emerald-100 bg-emerald-50 p-5 shadow-sm hover:bg-emerald-100/50 transition">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700">Pemasukan</p>
                    <p class="mt-3 text-2xl font-bold text-emerald-800">{{ $currencySymbol }}{{ number_format($monthlyIncome, 0, ',', '.') }}</p>
                </a>

                <a href="{{ route('transactions.index') }}" class="ui-card block rounded-lg border border-rose-100 bg-rose-50 p-5 shadow-sm hover:bg-rose-100/50 transition">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-rose-700">Pengeluaran</p>
                    <p class="mt-3 text-2xl font-bold text-rose-800">{{ $currencySymbol }}{{ number_format($monthlyExpense, 0, ',', '.') }}</p>
                </a>
            </div>

                <div class="grid gap-5 lg:grid-cols-[1.7fr_0.7fr]">

    {{-- Aktivitas Terbaru --}}
    <section class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">

        <div
            class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                    Aktivitas Terbaru
                </p>
                <h2 class="mt-1 text-lg font-semibold text-slate-950">
                    Transaksi terakhir
                </h2>
            </div>
        </div>

        @if ($recentItems->isEmpty())
            <div class="p-10 text-center">
                <div
                    class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                            d="M4 6h16M4 12h10M4 18h7" />
                    </svg>
                </div>

                <h3 class="text-lg font-semibold text-slate-950">
                    Belum ada transaksi
                </h3>

                <p class="mt-2 text-sm text-slate-500">
                    Mulai catat transaksi pertama untuk melihat aktivitas di dashboard.
                </p>

                <a href="{{ $transactionsCreateUrl }}"
                    class="mt-6 inline-flex h-11 items-center justify-center rounded-lg bg-emerald-600 px-5 text-sm font-semibold text-white hover:bg-emerald-700">
                    Tambah Transaksi
                </a>
            </div>
        @else
            <div class="divide-y divide-slate-100">

                @foreach ($recentItems as $transaction)
                    @php
                        $isIncome = $transaction->type === 'income';
                    @endphp

                    <div
                        class="flex flex-col gap-3 px-5 py-4 transition hover:bg-slate-50 sm:flex-row sm:items-center sm:justify-between">

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl
                            {{ $isIncome
                                ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100'
                                : 'bg-rose-50 text-rose-700 ring-1 ring-rose-100' }}">

                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.8">

                                    @if ($isIncome)
                                        <path d="M12 19V5" />
                                        <path d="m5 12 7-7 7 7" />
                                    @else
                                        <path d="M12 5v14" />
                                        <path d="m19 12-7 7-7-7" />
                                    @endif

                                </svg>
                            </div>

                            <div>
                                <p class="font-semibold text-slate-950">
                                    {{ $transaction->title }}
                                </p>

                                <p class="text-xs text-slate-500">
                                    {{ optional($transaction->account)->name ?? '-' }}
                                    •
                                    {{ optional($transaction->transaction_date)->format('d M Y') ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <p
                            class="font-bold {{ $isIncome ? 'text-emerald-700' : 'text-rose-700' }}">
                            {{ $isIncome ? '+' . $currencySymbol : '-' . $currencySymbol }}{{ number_format($transaction->amount, 0, ',', '.') }}
                        </p>
                    </div>
                @endforeach

            </div>
        @endif

    </section>

    {{-- Sidebar --}}
    <aside class="space-y-5">

        {{-- Aksi Cepat --}}
        <section
            class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-100 px-5 py-4">
                <p
                    class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                    Aksi Cepat
                </p>
            </div>

            <div class="p-4 space-y-3">

                <a href="{{ $transactionsIndexUrl }}"
                    class="group flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:border-sky-200 hover:bg-sky-50">

                    <div>
                        <p class="text-sm font-semibold text-slate-900">
                            Kelola Transaksi
                        </p>
                        <p class="text-xs text-slate-500">
                            Lihat dan tambah transaksi
                        </p>
                    </div>

                    <svg class="h-5 w-5 text-slate-400 transition group-hover:translate-x-1"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                <a href="{{ $accountsIndexUrl }}"
                    class="group flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:border-sky-200 hover:bg-sky-50">

                    <div>
                        <p class="text-sm font-semibold text-slate-900">
                            Kelola Akun
                        </p>
                        <p class="text-xs text-slate-500">
                            Dompet dan rekening
                        </p>
                    </div>

                    <svg class="h-5 w-5 text-slate-400 transition group-hover:translate-x-1"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                <a href="{{ $budgetUrl }}"
                    class="group flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:border-sky-200 hover:bg-sky-50">

                    <div>
                        <p class="text-sm font-semibold text-slate-900">
                            Kelola Budget
                        </p>
                        <p class="text-xs text-slate-500">
                            Pantau batas pengeluaran
                        </p>
                    </div>

                    <svg class="h-5 w-5 text-slate-400 transition group-hover:translate-x-1"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

            </div>

        </section>

        {{-- Akun Teratas --}}
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                Akun Teratas
            </p>

            <div class="mt-4 space-y-3">

                @forelse ($accountItems->sortByDesc('balance')->take(3) as $account)

                    <div
                        class="flex items-center justify-between rounded-lg bg-slate-50 p-3">

                        <div>
                            <p class="font-semibold text-slate-950">
                                {{ $account->name }}
                            </p>

                            <p class="text-xs text-slate-500">
                                {{ ucfirst($account->type ?? '-') }}
                            </p>
                        </div>

                        <p
                            class="font-bold {{ $account->balance < 0 ? 'text-rose-600' : 'text-slate-900' }}">
                            {{ $currencySymbol }}{{ number_format(abs($account->balance), 0, ',', '.') }}
                        </p>
                    </div>

                @empty
                    <p class="text-sm text-slate-500">
                        Belum ada akun.
                    </p>
                @endforelse

            </div>

        </section>

    </aside>

</div>
            </div>
        </div>
    </div>
@endsection
