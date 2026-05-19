@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-br from-blue-50 to-indigo-50 min-h-screen py-8">
    <div class="max-w-2xl mx-auto px-4">
        <!-- Header -->
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Detail Transaksi</h1>
                <p class="text-gray-500 mt-1">Informasi lengkap transaksi Anda</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('transactions.edit', $transaction) }}" class="p-3 bg-blue-500 text-white rounded-xl hover:shadow-lg transition-all duration-200" title="Edit">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>
                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-3 bg-red-500 text-white rounded-xl hover:shadow-lg transition-all duration-200" title="Hapus">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <!-- Transaction Type Badge -->
            <div class="mb-6">
                <span class="inline-block px-4 py-2 rounded-full text-white font-semibold {{ $transaction->type === 'income' ? 'bg-green-500' : 'bg-red-500' }}">
                    {{ $transaction->type === 'income' ? '📥 Pemasukan' : '📤 Pengeluaran' }}
                </span>
            </div>

            <!-- Title & Amount -->
            <div class="mb-8 pb-6 border-b-2 border-gray-100">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">{{ $transaction->title }}</h2>
                <p class="text-5xl font-bold {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                    {{ $transaction->type === 'income' ? '+' : '-' }}Rp{{ number_format($transaction->amount, 0, ',', '.') }}
                </p>
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Account -->
                <div class="bg-blue-50 rounded-xl p-4">
                    <p class="text-sm text-blue-600 font-semibold uppercase tracking-wide">Akun</p>
                    <p class="text-lg font-bold text-gray-800 mt-2">{{ $transaction->account->name }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ ucfirst($transaction->account->type) }}</p>
                </div>

                <!-- Category -->
                <div class="bg-purple-50 rounded-xl p-4">
                    <p class="text-sm text-purple-600 font-semibold uppercase tracking-wide">Kategori</p>
                    <p class="text-lg font-bold text-gray-800 mt-2">{{ $transaction->category->name }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ ucfirst($transaction->category->type) }}</p>
                </div>

                <!-- Date -->
                <div class="bg-orange-50 rounded-xl p-4">
                    <p class="text-sm text-orange-600 font-semibold uppercase tracking-wide">Tanggal</p>
                    <p class="text-lg font-bold text-gray-800 mt-2">{{ $transaction->transaction_date->format('d/m/Y') }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $transaction->transaction_date->format('l') }}</p>
                </div>

                <!-- Status -->
                <div class="bg-indigo-50 rounded-xl p-4">
                    <p class="text-sm text-indigo-600 font-semibold uppercase tracking-wide">Status</p>
                    <p class="text-lg font-bold text-gray-800 mt-2">Selesai</p>
                    <p class="text-sm text-gray-600 mt-1">✓ Tercatat</p>
                </div>
            </div>

            <!-- Description -->
            @if($transaction->description)
                <div class="mb-8">
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Catatan</p>
                    <div class="bg-gray-50 rounded-xl p-4 text-gray-700">
                        {{ $transaction->description }}
                    </div>
                </div>
            @endif

            <!-- Tags -->
            @if($transaction->tags->count() > 0)
                <div class="mb-8">
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">Tag</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($transaction->tags as $tag)
                            <span class="text-sm font-semibold text-white px-3 py-1 rounded-full" style="background-color: {{ $tag->color ?? '#6B7280' }}">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Timestamps -->
            <div class="border-t-2 border-gray-100 pt-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Dibuat</p>
                        <p class="text-sm text-gray-800 mt-1">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Terakhir Diubah</p>
                        <p class="text-sm text-gray-800 mt-1">{{ $transaction->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('transactions.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300 transition-colors font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Daftar
            </a>
        </div>
    </div>
</div>
@endsection
