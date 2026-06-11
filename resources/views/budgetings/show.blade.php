@extends('layouts.app')

@section('content')
    @php
        $limitAmount = (float) $budgeting->limit_amount;
        $usedAmount = (float) $budgeting->spent_amount;
        $remainingAmount = $limitAmount - $usedAmount;
        $progressPercent = $limitAmount > 0 ? ($usedAmount / $limitAmount) * 100 : 0;
        $isOver = $remainingAmount < 0;
        $isWarning = ! $isOver && $progressPercent >= 80;
        $barColor = $isOver ? 'bg-rose-500' : ($isWarning ? 'bg-amber-500' : 'bg-emerald-500');
        $statusColor = $isOver ? 'bg-rose-50 text-rose-700 ring-rose-100' : ($isWarning ? 'bg-amber-50 text-amber-700 ring-amber-100' : 'bg-emerald-50 text-emerald-700 ring-emerald-100');
        $statusText = $isOver ? 'Lewat limit' : ($isWarning ? 'Hampir penuh' : 'Terkendali');
    @endphp
<div class="min-h-screen bg-[#f6f7f9]">
    <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 sm:py-10 lg:px-8">
        <!-- Header -->
        <div class="mb-8 border-b border-slate-200 pb-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Detail Budget</p>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                        {{ $budgeting->name }}
                    </h1>
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Kategori: {{ $budgeting->category->name ?? 'Semua Kategori' }} | 
                        Kelola budget untuk {{ ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][$budgeting->month] ?? '-' }} {{ $budgeting->year }}
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('budgetings.index') }}"
                        class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-slate-200 hover:bg-slate-50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali
                    </a>
                    <a href="{{ route('budgetings.edit', $budgeting->id) }}"
                        class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-amber-600 px-4 text-sm font-semibold text-white shadow-sm shadow-amber-700/15 hover:bg-amber-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Left Column: Primary Info -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Status & Progress Badge -->
                <div>
                    <span class="inline-flex rounded-md px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] ring-1 {{ $statusColor }}">
                        {{ $statusText }}
                    </span>
                </div>

                <!-- Main Progress Section -->
                <section class="overflow-hidden rounded-lg bg-slate-950 p-6 text-white shadow-lg shadow-slate-900/10">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Progress Pemakaian</p>
                            <p class="mt-3 text-5xl font-bold tracking-tight">{{ number_format(min(100, $progressPercent), 1) }}%</p>
                        </div>
                        <div class="rounded-lg border border-white/10 bg-white/[0.06] px-4 py-3">
                            <p class="text-xs text-slate-400">Terakhir diperbarui</p>
                            <p class="mt-1 text-sm font-semibold text-white">{{ $budgeting->updated_at->format('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <div class="flex items-center justify-between gap-4 text-sm mb-3">
                            <span class="font-medium text-slate-300">Terpakai {{ $currencySymbol }}{{ number_format($usedAmount, 0, ',', '.') }}</span>
                            <span class="font-semibold text-white">Limit {{ $currencySymbol }}{{ number_format($limitAmount, 0, ',', '.') }}</span>
                        </div>
                        <div class="h-3 overflow-hidden rounded-full bg-white/10">
                            <div class="h-full rounded-full {{ $barColor }} transition-all" style="width: {{ min(100, $progressPercent) }}%"></div>
                        </div>
                    </div>
                </section>

                <!-- Amount Cards -->
                <div class="grid gap-4 md:grid-cols-3">
                    <div class="ui-card rounded-lg border border-slate-200 bg-white p-5 shadow-sm hover:border-slate-300">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Limit Budget</p>
                        <p class="mt-3 text-xl font-bold text-slate-950">{{ $currencySymbol }}{{ number_format($limitAmount, 0, ',', '.') }}</p>
                    </div>

                    <div class="ui-card rounded-lg border border-slate-200 bg-slate-50 p-5 shadow-sm hover:border-slate-300">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Terpakai</p>
                        <p class="mt-3 text-xl font-bold text-slate-950">{{ $currencySymbol }}{{ number_format($usedAmount, 0, ',', '.') }}</p>
                    </div>

                    <div class="ui-card rounded-lg {{ $remainingAmount < 0 ? 'border-rose-100 bg-rose-50' : 'border-emerald-100 bg-emerald-50' }} p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] {{ $remainingAmount < 0 ? 'text-rose-600' : 'text-emerald-600' }}">Sisa</p>
                        <p class="mt-3 text-xl font-bold {{ $remainingAmount < 0 ? 'text-rose-700' : 'text-emerald-700' }}">
                            {{ $remainingAmount < 0 ? '-' . $currencySymbol : $currencySymbol }}{{ number_format(abs($remainingAmount), 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <!-- Category Info -->
                <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Kategori</p>
                    <div class="mt-4 flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 19V5" />
                                <path d="M8 17V9" />
                                <path d="M12 15V7" />
                                <path d="M16 19v-6" />
                                <path d="M20 17V4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-slate-600">Kategori Pengeluaran</p>
                            <p class="text-lg font-semibold text-slate-900">{{ $budgeting->category->name ?? 'Semua Kategori (Umum)' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Linked Transactions -->
                <section class="rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Riwayat</p>
                            <h2 class="mt-1 text-base font-semibold text-slate-950">Transaksi Terhubung</h2>
                        </div>
                        <span class="inline-flex rounded-md bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100">
                            {{ $budgeting->transactions->count() }} transaksi
                        </span>
                    </div>

                    @if ($budgeting->transactions->isEmpty())
                        <div class="p-10 text-center">
                            <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-slate-100 text-slate-400">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-slate-600">Belum ada transaksi terhubung</p>
                            <p class="mt-1 text-xs text-slate-400">Hubungkan transaksi ke budget ini saat mencatat di halaman Transaksi.</p>
                            <a href="{{ route('transactions.create') }}" class="mt-4 inline-flex h-9 items-center gap-2 rounded-lg bg-emerald-600 px-3 text-sm font-semibold text-white hover:bg-emerald-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Transaksi
                            </a>
                        </div>
                    @else
                        <ul class="divide-y divide-slate-100">
                            @foreach ($budgeting->transactions as $tx)
                                <li class="flex items-center justify-between gap-4 px-5 py-3.5 hover:bg-slate-50">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-rose-50 text-rose-600">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 13l-5 5m0 0l-5-5m5 5V6" /></svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-semibold text-slate-900">{{ $tx->title }}</p>
                                            <p class="text-xs text-slate-500">{{ $tx->transaction_date->format('d M Y') }}{{ $tx->category ? ' · ' . $tx->category->name : '' }}</p>
                                        </div>
                                    </div>
                                    <p class="shrink-0 text-sm font-bold text-rose-600">
                                        -{{ $currencySymbol }}{{ number_format($tx->amount, 0, ',', '.') }}
                                    </p>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>
            </div>

            <!-- Right Column: Timeline & Info -->
            <div class="space-y-6">
                <!-- Created & Updated Info -->
                <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Informasi Sistem</p>
                    <div class="mt-4 space-y-4 text-sm">
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-[0.1em]">Dibuat Pada</p>
                            <p class="mt-1 font-medium text-slate-900">
                                {{ optional($budgeting->created_at)->format('d M Y') ?? '-' }}
                            </p>
                            <p class="text-xs text-slate-500">
                                {{ optional($budgeting->created_at)->format('H:i') ?? '-' }}
                            </p>
                        </div>
                        <div class="border-t border-slate-100 pt-4">
                            <p class="text-xs text-slate-500 uppercase tracking-[0.1em]">Terakhir Diperbarui</p>
                            <p class="mt-1 font-medium text-slate-900">
                                {{ optional($budgeting->updated_at)->format('d M Y') ?? '-' }}
                            </p>
                            <p class="text-xs text-slate-500">
                                {{ optional($budgeting->updated_at)->format('H:i') ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700">Status</p>
                    <div class="mt-4 flex items-center gap-2">
                        <span class="inline-block h-3 w-3 rounded-full bg-emerald-500"></span>
                        <p class="text-sm font-semibold text-emerald-900">Aktif</p>
                    </div>
                    <p class="mt-2 text-xs leading-5 text-emerald-700">
                        Budget ini sedang aktif dan digunakan untuk tracking pengeluaran Anda.
                    </p>
                </div>

                <!-- Quick Actions -->
                <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 mb-4">Aksi Cepat</p>
                    <div class="space-y-2">
                        <a href="{{ route('budgetings.edit', $budgeting->id) }}"
                            class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Budget
                        </a>
                        <form action="{{ route('budgetings.destroy', $budgeting->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus budget ini?');" class="inline-block w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus Budget
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Info Card -->
        <div class="mt-8 rounded-lg border border-blue-200 bg-blue-50 p-6">
            <div class="flex gap-4">
                <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-700">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-blue-900">Cara Menggunakan Budget</h3>
                    <ul class="mt-2 space-y-1 text-xs text-blue-700">
                        <li>• Budget ini membantu Anda melacak pengeluaran kategori ini</li>
                        <li>• Setiap transaksi yang Anda input akan dikurangkan dari limit</li>
                        <li>• Anda akan mendapat notifikasi jika mendekati atau melampaui limit</li>
                        <li>• Edit atau hapus budget kapan saja sesuai kebutuhan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
