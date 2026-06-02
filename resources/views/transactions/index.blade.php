@extends('layouts.app')

@section('content')
<div class="bg-[#f6f7f9] min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 rounded-3xl bg-white border border-slate-200 p-6 shadow-sm">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-slate-950">Transaksi</h1>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Kelola semua transaksi keuangan Anda</p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <a href="{{ route('transactions.createRecurring') }}" class="ui-button inline-flex h-11 items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-700 shadow-sm hover:border-slate-300 hover:bg-slate-50 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Recurring Transaction
                    </a>
                    <a href="{{ route('transactions.create') }}" class="ui-button inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-emerald-600 px-5 text-sm font-semibold text-white shadow-sm shadow-emerald-700/15 hover:bg-emerald-700 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Transaction
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if($transactions->count() > 0)
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                @php
                    $totalIncome = $transactions->where('type', 'income')->sum('amount');
                    $totalExpense = $transactions->where('type', 'expense')->sum('amount');
                    $balance = $accountBalance;
                @endphp
                
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex justify-between items-start gap-4">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-slate-500">Pemasukan</p>
                            <p class="mt-3 text-3xl font-bold text-green-700">Rp{{ number_format($totalIncome, 0, ',', '.') }}</p>
                        </div>
                        <div class="mt-1 rounded-2xl bg-emerald-50 p-3 text-emerald-700">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex justify-between items-start gap-4">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-slate-500">Pengeluaran</p>
                            <p class="mt-3 text-3xl font-bold text-red-700">Rp{{ number_format($totalExpense, 0, ',', '.') }}</p>
                        </div>
                        <div class="mt-1 rounded-2xl bg-red-50 p-3 text-red-700">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex justify-between items-start gap-4">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-slate-500">Saldo</p>
                            <p class="mt-3 text-3xl font-bold {{ $balance >= 0 ? 'text-slate-950' : 'text-orange-700' }}">Rp{{ number_format($balance, 0, ',', '.') }}</p>
                        </div>
                        <div class="mt-1 rounded-2xl {{ $balance >= 0 ? 'bg-sky-50 text-sky-700' : 'bg-orange-50 text-orange-700' }} p-3">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recurring Transactions Section -->
            @if($recurringTransactions->count() > 0)
                <div class="mb-8">
                    <div class="mb-4 flex items-center gap-2 text-slate-950">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <h2 class="text-xl font-semibold">Transaksi Berulang Aktif</h2>
                    </div>
                    <div class="space-y-3">
                        @foreach($recurringTransactions as $recurring)
                            <div class="relative rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                <div class="absolute inset-y-0 left-0 w-1 rounded-l-lg bg-gradient-to-b from-amber-500 to-orange-400"></div>
                                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between pl-4">
                                    <div class="flex items-center gap-4 flex-1">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-50 text-orange-700">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                        </div>

                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-slate-950">{{ $recurring->title }}</h3>
                                            <div class="mt-2 flex flex-wrap items-center gap-2 text-sm text-slate-500">
                                                <span class="rounded-full bg-slate-100 px-2 py-1">@switch($recurring->frequency)
                                                    @case('daily') Harian @break
                                                    @case('weekly') Mingguan @break
                                                    @case('monthly') Bulanan @break
                                                    @case('yearly') Tahunan @break
                                                @endswitch</span>
                                                <span class="rounded-full bg-slate-100 px-2 py-1">{{ $recurring->account->name }}</span>
                                                <span>Mulai {{ $recurring->start_date->format('d M Y') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-2xl font-bold text-red-600">-Rp{{ number_format($recurring->amount, 0, ',', '.') }}</p>
                                        <p class="text-sm text-slate-500 mt-1">per periode</p>
                                        <div class="mt-3 flex justify-end gap-2">
                                            <a href="{{ route('transactions.showRecurring', $recurring) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-blue-600 hover:border-slate-300 hover:bg-slate-50 transition-colors" title="Lihat">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('transactions.editRecurring', $recurring) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-orange-600 hover:border-slate-300 hover:bg-slate-50 transition-colors" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('transactions.destroyRecurring', $recurring) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-red-600 hover:border-slate-300 hover:bg-slate-50 transition-colors" title="Hapus" onclick="return confirm('Hapus recurring transaction ini?')">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Transactions List -->
            <div class="space-y-3">
                    @foreach($transactions as $transaction)
                    <div class="relative rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-slate-300 hover:shadow-md">
                        <div class="absolute inset-y-0 left-0 w-1 rounded-l-lg {{ $transaction->type === 'income' ? 'bg-gradient-to-b from-emerald-500 to-teal-400' : 'bg-gradient-to-b from-rose-500 to-red-400' }}"></div>
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between pl-4">
                            <div class="flex items-center gap-4 flex-1">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $transaction->type === 'income' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                                    </svg>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-slate-950 truncate">{{ $transaction->title }}</h3>
                                    <div class="mt-2 flex flex-wrap items-center gap-2 text-sm text-slate-500">
                                        <span class="rounded-full bg-slate-100 px-2 py-1">{{ $transaction->category->name }}</span>
                                        <span class="rounded-full bg-slate-100 px-2 py-1">{{ $transaction->account->name }}</span>
                                        <span>{{ $transaction->transaction_date->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>

                            @if($transaction->tags->count() > 0)
                                <div class="flex flex-wrap gap-1 px-1 text-sm">
                                    @foreach($transaction->tags->take(2) as $tag)
                                        <span class="rounded-full px-2 py-1 text-white" style="background-color: {{ $tag->color ?? '#6B7280' }}">{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="flex flex-col items-end gap-3 text-right">
                                <p class="text-2xl font-bold {{ $transaction->type === 'income' ? 'text-emerald-700' : 'text-rose-700' }}">
                                    {{ $transaction->type === 'income' ? '+' : '-' }}Rp{{ number_format($transaction->amount, 0, ',', '.') }}
                                </p>
                                <div class="flex gap-2">
                                    <a href="{{ route('transactions.show', $transaction) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50 transition-colors" title="Lihat">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('transactions.edit', $transaction) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50 transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50 transition-colors" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                {{ $transactions->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="rounded-2xl border border-slate-200 bg-white p-16 text-center shadow-sm">
                <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-slate-100 text-slate-500">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-slate-950 mb-2">Belum ada transaksi</h3>
                <p class="text-sm text-slate-600 mb-6">Mulai catat transaksi keuangan Anda sekarang</p>
                <a href="{{ route('transactions.create') }}" class="ui-button inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-sm shadow-emerald-700/15 hover:bg-emerald-700 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Transaksi Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
