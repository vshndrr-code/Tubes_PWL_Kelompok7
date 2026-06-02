@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-br from-blue-50 to-indigo-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Header -->
<div class="flex flex-col gap-4 md:flex-row md:justify-between md:items-center mb-8">
                <div>
                    <h1 class="text-4xl font-bold text-gray-800">Transaksi</h1>
                    <p class="text-gray-500 mt-1">Kelola semua transaksi keuangan Anda</p>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                    <a href="{{ route('transactions.createRecurring') }}" class="inline-flex items-center gap-2 rounded-2xl bg-emerald-100 px-5 py-2.5 text-sm font-semibold text-emerald-800 hover:bg-emerald-200 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Recurring Transaction
                    </a>
                    <a href="{{ route('transactions.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-emerald-500 hover:bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Transaction
                    </a>
                </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-xl border border-green-200 flex items-center gap-2">
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
                    $balance = $totalIncome - $totalExpense;
                @endphp
                
                <div class="bg-white rounded-2xl p-6 shadow-md border-l-4 border-green-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Pemasukan</p>
                            <p class="text-2xl font-bold text-green-600 mt-2">Rp{{ number_format($totalIncome, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-md border-l-4 border-red-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Pengeluaran</p>
                            <p class="text-2xl font-bold text-red-600 mt-2">Rp{{ number_format($totalExpense, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-red-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-md border-l-4 {{ $balance >= 0 ? 'border-blue-500' : 'border-orange-500' }}">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Saldo</p>
                            <p class="text-2xl font-bold {{ $balance >= 0 ? 'text-blue-600' : 'text-orange-600' }} mt-2">Rp{{ number_format($balance, 0, ',', '.') }}</p>
                        </div>
                        <div class="{{ $balance >= 0 ? 'bg-blue-100' : 'bg-orange-100' }} p-3 rounded-lg">
                            <svg class="w-6 h-6 {{ $balance >= 0 ? 'text-blue-600' : 'text-orange-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recurring Transactions Section -->
            @if($recurringTransactions->count() > 0)
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Transaksi Berulang Aktif
                    </h2>
                    <div class="space-y-3">
                        @foreach($recurringTransactions as $recurring)
                            <div class="bg-white rounded-2xl p-4 shadow-md border-l-4 border-orange-500">
                                <div class="flex items-center justify-between">
                                    <!-- Left Side: Icon & Info -->
                                    <div class="flex items-center gap-4 flex-1">
                                        <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                        </div>

                                        <div class="flex-1">
                                            <h3 class="font-semibold text-gray-800 text-lg">{{ $recurring->title }}</h3>
                                            <div class="flex items-center gap-3 mt-1">
                                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                                                    @switch($recurring->frequency)
                                        @case('daily')
                                            Harian
                                        @break
                                        @case('weekly')
                                            Mingguan
                                        @break
                                        @case('monthly')
                                            Bulanan
                                        @break
                                        @case('yearly')
                                            Tahunan
                                        @endswitch
                                                </span>
                                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">{{ $recurring->account->name }}</span>
                                                <span class="text-xs text-gray-500">Mulai {{ $recurring->start_date->format('d M Y') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Side: Amount -->
                                    <div class="text-right">
                                        <p class="text-2xl font-bold text-red-600">-Rp{{ number_format($recurring->amount, 0, ',', '.') }}</p>
                                        <p class="text-xs text-gray-500 mt-1">per periode</p>
                                        <div class="flex gap-2 mt-3 justify-end">
                                            <a href="{{ route('transactions.showRecurring', $recurring) }}" class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 p-2 rounded-lg transition-colors" title="Lihat">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('transactions.editRecurring', $recurring) }}" class="text-orange-600 hover:text-orange-800 hover:bg-orange-50 p-2 rounded-lg transition-colors" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('transactions.destroyRecurring', $recurring) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 hover:bg-red-50 p-2 rounded-lg transition-colors" title="Hapus" onclick="return confirm('Hapus recurring transaction ini?')">
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
                    <div class="bg-white rounded-2xl p-4 shadow-md hover:shadow-lg transition-all duration-200 border-l-4 {{ $transaction->type === 'income' ? 'border-green-500' : 'border-red-500' }}">
                        <div class="flex items-center justify-between">
                            <!-- Left Side: Icon & Info -->
                            <div class="flex items-center gap-4 flex-1">
                                <div class="w-12 h-12 rounded-full {{ $transaction->type === 'income' ? 'bg-green-100' : 'bg-red-100' }} flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                                    </svg>
                                </div>

                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-800 text-lg">{{ $transaction->title }}</h3>
                                    <div class="flex items-center gap-3 mt-1">
                                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">{{ $transaction->category->name }}</span>
                                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">{{ $transaction->account->name }}</span>
                                        <span class="text-xs text-gray-500">{{ $transaction->transaction_date->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Middle: Tags -->
                            @if($transaction->tags->count() > 0)
                                <div class="flex gap-1 mx-4">
                                    @foreach($transaction->tags->take(2) as $tag)
                                        <span class="text-xs font-medium text-white px-2 py-1 rounded-full" style="background-color: {{ $tag->color ?? '#6B7280' }}">
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Right Side: Amount & Actions -->
                            <div class="text-right">
                                <p class="text-2xl font-bold {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->type === 'income' ? '+' : '-' }}Rp{{ number_format($transaction->amount, 0, ',', '.') }}
                                </p>
                                <div class="flex gap-2 mt-2 justify-end">
                                    <a href="{{ route('transactions.show', $transaction) }}" class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 p-2 rounded-lg transition-colors" title="Lihat">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('transactions.edit', $transaction) }}" class="text-yellow-600 hover:text-yellow-800 hover:bg-yellow-50 p-2 rounded-lg transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 hover:bg-red-50 p-2 rounded-lg transition-colors" title="Hapus">
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
            <div class="bg-white rounded-2xl p-16 text-center shadow-md">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Belum ada transaksi</h3>
                <p class="text-gray-500 mb-6">Mulai catat transaksi keuangan Anda sekarang</p>
                <a href="{{ route('transactions.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-emerald-500 hover:bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Transaksi Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
