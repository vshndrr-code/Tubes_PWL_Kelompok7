@extends('layouts.app')

@push('head')
<style>
    @keyframes soft-enter {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (prefers-reduced-motion: no-preference) {
        .ui-reveal { animation: soft-enter .42s ease-out both; }
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
        .ui-button:hover { transform: translateY(-2px); }
    }
</style>
@endpush

@section('content')
    @php
        $progressPercent = ($savingsGoal->current_amount / $savingsGoal->target_amount) * 100;
        $isCompleted = $savingsGoal->status === 'completed';
        $isCancelled = $savingsGoal->status === 'cancelled';
        $barColor = $isCompleted ? 'bg-emerald-500' : ($isCancelled ? 'bg-rose-500' : 'bg-sky-500');
        $statusColor = $isCompleted ? 'bg-emerald-50 text-emerald-700 ring-emerald-100' : ($isCancelled ? 'bg-rose-50 text-rose-700 ring-rose-100' : 'bg-sky-50 text-sky-700 ring-sky-100');
    @endphp

    <div class="min-h-screen bg-[#f6f7f9] text-slate-900">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-10 lg:px-8">
            <div class="mb-7 flex flex-col gap-4 border-b border-slate-200 pb-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Detail Savings Goal</p>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">{{ $savingsGoal->name }}</h1>
                    @if ($savingsGoal->deadline)
                        <p class="mt-2 text-sm leading-6 text-slate-600">Target: {{ $savingsGoal->deadline->format('d M Y') }}</p>
                    @endif
                </div>

                <div class="flex flex-col gap-2 sm:flex-row">
                    <a href="{{ route('savings-goals.index') }}" class="ui-button inline-flex h-11 items-center justify-center rounded-lg border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                        Kembali
                    </a>
                    <a href="{{ route('savings-goals.edit', $savingsGoal->id) }}" class="ui-button inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-sky-600 px-4 text-sm font-semibold text-white shadow-sm shadow-sky-700/15 hover:bg-sky-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                </div>
            </div>

            <div class="mb-5 grid gap-6 lg:grid-cols-[minmax(0,1fr)_360px]">
                <main class="space-y-5">
                    <!-- Status Badge -->
                    <div>
                        <span class="inline-flex rounded-md px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] ring-1 {{ $statusColor }}">
                            {{ ucfirst($savingsGoal->status) }}
                        </span>
                    </div>

                    <!-- Main Progress Section -->
                    <section class="ui-reveal overflow-hidden rounded-lg bg-slate-950 p-6 text-white shadow-lg shadow-slate-900/10">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Progress Keseluruhan</p>
                                <p class="mt-3 text-5xl font-bold tracking-tight">{{ number_format(min(100, $progressPercent), 1) }}%</p>
                            </div>
                            <div class="rounded-lg border border-white/10 bg-white/[0.06] px-4 py-3">
                                <p class="text-xs text-slate-400">Terakhir diperbarui</p>
                                <p class="mt-1 text-sm font-semibold text-white">{{ $savingsGoal->updated_at->format('d M Y') }}</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="flex items-center justify-between gap-4 text-sm mb-3">
                                <span class="font-medium text-slate-300">{{ $currencySymbol }}{{ number_format($savingsGoal->current_amount, 0, ',', '.') }}</span>
                                <span class="font-semibold text-white">{{ $currencySymbol }}{{ number_format($savingsGoal->target_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="h-3 overflow-hidden rounded-full bg-white/10">
                                <div class="h-full rounded-full {{ $barColor }} transition-all" style="width: {{ min(100, $progressPercent) }}%"></div>
                            </div>
                        </div>
                    </section>

                    <!-- Amount Cards -->
                    <div class="grid gap-4 md:grid-cols-3">
                        <div class="ui-card rounded-lg border border-sky-100 bg-sky-50 p-5 shadow-sm hover:border-sky-200 min-w-0">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-600">Saat Ini</p>
                            <p class="mt-3 text-xl font-bold text-sky-700 break-all sm:text-2xl lg:text-lg xl:text-2xl">{{ $currencySymbol }}{{ number_format($savingsGoal->current_amount, 0, ',', '.') }}</p>
                        </div>

                        <div class="ui-card rounded-lg border border-slate-200 bg-white p-5 shadow-sm hover:border-slate-300 min-w-0">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Target</p>
                            <p class="mt-3 text-xl font-bold text-slate-950 break-all sm:text-2xl lg:text-lg xl:text-2xl">{{ $currencySymbol }}{{ number_format($savingsGoal->target_amount, 0, ',', '.') }}</p>
                        </div>

                        <div class="ui-card rounded-lg {{ max(0, $savingsGoal->target_amount - $savingsGoal->current_amount) <= 0 ? 'border-emerald-100 bg-emerald-50' : 'border-amber-100 bg-amber-50' }} p-5 shadow-sm min-w-0">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] {{ max(0, $savingsGoal->target_amount - $savingsGoal->current_amount) <= 0 ? 'text-emerald-600' : 'text-amber-600' }}">Sisa</p>
                            <p class="mt-3 text-xl font-bold {{ max(0, $savingsGoal->target_amount - $savingsGoal->current_amount) <= 0 ? 'text-emerald-700' : 'text-amber-700' }} break-all sm:text-2xl lg:text-lg xl:text-2xl">{{ $currencySymbol }}{{ number_format(max(0, $savingsGoal->target_amount - $savingsGoal->current_amount), 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Key Information -->
                    <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Informasi Penting</p>
                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-lg bg-slate-50 p-4 ring-1 ring-slate-200">
                                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Dibuat</p>
                                <p class="mt-2 text-lg font-semibold text-slate-950">{{ $savingsGoal->created_at->format('d M Y') }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $savingsGoal->created_at->format('H:i') }}</p>
                            </div>
                            <div class="rounded-lg bg-slate-50 p-4 ring-1 ring-slate-200">
                                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Terakhir Diperbarui</p>
                                <p class="mt-2 text-lg font-semibold text-slate-950">{{ $savingsGoal->updated_at->format('d M Y') }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $savingsGoal->updated_at->format('H:i') }}</p>
                            </div>
                            @if ($savingsGoal->deadline)
                                <div class="rounded-lg bg-slate-50 p-4 ring-1 ring-slate-200">
                                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Hari Tersisa</p>
                                    <p class="mt-2 text-lg font-semibold {{ (int) max(0, now()->diffInDays($savingsGoal->deadline)) <= 7 ? 'text-rose-700' : 'text-slate-950' }}">{{ (int) max(0, now()->diffInDays($savingsGoal->deadline)) }} hari</p>
                                </div>
                            @endif
                            @if ($savingsGoal->account)
                                <div class="rounded-lg bg-sky-50 p-4 ring-1 ring-sky-200">
                                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-sky-600">Akun Terkait</p>
                                    <p class="mt-2 text-lg font-semibold text-sky-900">
                                        <a href="{{ route('accounts.show', $savingsGoal->account->id) }}" class="hover:underline">
                                            {{ $savingsGoal->account->name }}
                                        </a>
                                    </p>
                                </div>
                            @endif
                        </div>
                    </section>

                    <!-- Contribution Rate -->
                    <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Tingkat Kontribusi</p>
                        @if ($savingsGoal->created_at->diffInDays(now()) > 0)
                            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                                <div class="rounded-lg bg-slate-50 p-4 ring-1 ring-slate-200">
                                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Per Hari</p>
                                    <p class="mt-2 text-lg font-semibold text-slate-950">{{ $currencySymbol }}{{ number_format($savingsGoal->current_amount / max(1, $savingsGoal->created_at->diffInDays(now())), 0, ',', '.') }}</p>
                                </div>
                                <div class="rounded-lg bg-slate-50 p-4 ring-1 ring-slate-200">
                                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Per Bulan</p>
                                    <p class="mt-2 text-lg font-semibold text-slate-950">{{ $currencySymbol }}{{ number_format(($savingsGoal->current_amount / max(1, $savingsGoal->created_at->diffInDays(now()))) * 30, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @else
                            <p class="mt-4 text-sm text-slate-500">Goal baru dibuat - periksa kembali nanti untuk statistik</p>
                        @endif
                    </section>

                    <!-- Linked Transactions -->
                    <section class="rounded-lg border border-slate-200 bg-white shadow-sm">
                        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Riwayat</p>
                                <h2 class="mt-1 text-base font-semibold text-slate-950">Transaksi Terhubung</h2>
                            </div>
                            <span class="inline-flex rounded-md bg-sky-50 px-2.5 py-1 text-xs font-semibold text-sky-700 ring-1 ring-sky-100">
                                {{ $savingsGoal->transactions->count() }} transaksi
                            </span>
                        </div>

                        @if ($savingsGoal->transactions->isEmpty())
                            <div class="p-10 text-center">
                                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-slate-100 text-slate-400">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-slate-600">Belum ada transaksi terhubung</p>
                                <p class="mt-1 text-xs text-slate-400">Hubungkan transaksi ke goal ini saat mencatat di halaman Transaksi.</p>
                                <a href="{{ route('transactions.create') }}" class="mt-4 inline-flex h-9 items-center gap-2 rounded-lg bg-sky-600 px-3 text-sm font-semibold text-white hover:bg-sky-700">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Tambah Transaksi
                                </a>
                            </div>
                        @else
                            <ul class="divide-y divide-slate-100">
                                @foreach ($savingsGoal->transactions as $tx)
                                    @php
                                        $isIncome = $tx->type === 'income';
                                    @endphp
                                    <li class="flex items-center justify-between gap-4 px-5 py-3.5 hover:bg-slate-50">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg {{ $isIncome ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-600' }}">
                                                @if ($isIncome)
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 11l5-5m0 0l5 5m-5-5v12" /></svg>
                                                @else
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 13l-5 5m0 0l-5-5m5 5V6" /></svg>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-semibold text-slate-900">{{ $tx->title }}</p>
                                                <p class="text-xs text-slate-500">{{ $tx->transaction_date->format('d M Y') }}{{ $tx->category ? ' · ' . $tx->category->name : '' }}</p>
                                            </div>
                                        </div>
                                        <p class="shrink-0 text-sm font-bold {{ $isIncome ? 'text-emerald-700' : 'text-rose-600' }}">
                                            {{ $isIncome ? '+' : '-' }}{{ $currencySymbol }}{{ number_format($tx->amount, 0, ',', '.') }}
                                        </p>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </section>
                </main>

                <aside class="space-y-5">
                    <!-- Status Section -->
                    <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Status</p>
                        <div class="mt-4 flex items-center gap-2">
                            <span class="inline-block h-3 w-3 rounded-full {{ $isCompleted ? 'bg-emerald-500' : ($isCancelled ? 'bg-rose-500' : 'bg-sky-500') }}"></span>
                            <p class="text-sm font-semibold {{ $isCompleted ? 'text-emerald-900' : ($isCancelled ? 'text-rose-900' : 'text-sky-900') }}">
                                {{ $isCompleted ? 'Selesai' : ($isCancelled ? 'Dibatalkan' : 'Aktif') }}
                            </p>
                        </div>
                        <p class="mt-3 text-xs leading-5 {{ $isCompleted ? 'text-emerald-700' : ($isCancelled ? 'text-rose-700' : 'text-sky-700') }}">
                            {{ $isCompleted ? 'Goal ini telah berhasil dicapai!' : ($isCancelled ? 'Goal ini telah dibatalkan.' : 'Goal ini sedang aktif dan sedang Anda targetkan.') }}
                        </p>
                    </section>

                    <!-- Actions -->
                    <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 mb-4">Aksi</p>
                        <div class="space-y-2">
                            <a href="{{ route('savings-goals.edit', $savingsGoal->id) }}" class="ui-button flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Goal
                            </a>
                            <form method="POST" action="{{ route('savings-goals.destroy', $savingsGoal->id) }}" onsubmit="return confirm('Yakin ingin menghapus goal ini?');" class="block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ui-button flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus Goal
                                </button>
                            </form>
                        </div>
                    </section>
                </aside>
            </div>
        </div>
    </div>
@endsection
