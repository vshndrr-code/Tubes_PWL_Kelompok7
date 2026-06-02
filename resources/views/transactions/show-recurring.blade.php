@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen">
    <div class="max-w-2xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $recurringTransaction->title }}</h1>
                <p class="text-gray-500 mt-1">Detail Recurring Transaction</p>
            </div>
            <a href="{{ route('transactions.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
        </div>

        <!-- Details Card -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8 space-y-6">
            <!-- Judul -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Transaksi</label>
                <p class="text-lg font-medium text-gray-900">{{ $recurringTransaction->title }}</p>
            </div>

            <!-- Amount -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal</label>
                    <p class="text-2xl font-bold text-red-600">Rp{{ number_format($recurringTransaction->amount, 0, ',', '.') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Per Periode</label>
                    <p class="text-lg font-medium text-gray-900">
                        @switch($recurringTransaction->frequency)
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
                    </p>
                </div>
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mulai Tanggal</label>
                    <p class="text-lg font-medium text-gray-900">{{ $recurringTransaction->start_date->format('d M Y') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pembaruan Terakhir</label>
                    <p class="text-lg font-medium text-gray-900">{{ $recurringTransaction->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>

            <!-- Account & Category -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Akun</label>
                    <p class="text-lg font-medium text-gray-900">{{ $recurringTransaction->account->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                    <p class="text-lg font-medium text-gray-900">{{ $recurringTransaction->category->name ?? 'Tidak ada' }}</p>
                </div>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <div class="inline-block px-4 py-2 rounded-full {{ $recurringTransaction->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $recurringTransaction->active ? 'Aktif' : 'Tidak Aktif' }}
                </div>
            </div>

            <!-- Catatan -->
            @if($recurringTransaction->description)
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan</label>
                    <p class="text-gray-900">{{ $recurringTransaction->description }}</p>
                </div>
            @endif

            <!-- Next Occurrence -->
            @if($recurringTransaction->next_occurrence_date && $recurringTransaction->active)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-800">
                        <strong>Pengurangan berikutnya:</strong> {{ $recurringTransaction->next_occurrence_date->format('d M Y') }}
                    </p>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex gap-3 border-t border-gray-200 pt-6">
                <a href="{{ route('transactions.index') }}" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium text-center">
                    Kembali
                </a>
                <a href="{{ route('transactions.editRecurring', $recurringTransaction) }}" class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium text-center">
                    Edit
                </a>
                <form action="{{ route('transactions.destroyRecurring', $recurringTransaction) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium" onclick="return confirm('Hapus recurring transaction ini?')">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
