@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen">
    <div class="max-w-2xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Edit Recurring Transaction</h1>
            <p class="text-gray-500 mt-1">Perbarui periode, nominal, judul, dan catatan.</p>
        </div>

        <!-- Form Card -->
        <form action="{{ route('transactions.updateRecurring', $recurringTransaction) }}" method="POST" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Periode</label>
                <div class="grid grid-cols-2 gap-2" id="frequency-selector">
                    <label class="frequency-option cursor-pointer rounded-2xl border-2 border-gray-200 bg-white px-4 py-3 text-center transition hover:border-emerald-400" data-value="daily">
                        <input type="radio" name="recurring_frequency" value="daily" class="sr-only" @checked($recurringTransaction->frequency === 'daily')>
                        <span class="block text-sm font-semibold text-gray-900">Harian</span>
                        <span class="text-xs text-gray-500">Setiap hari</span>
                    </label>
                    <label class="frequency-option cursor-pointer rounded-2xl border-2 border-gray-200 bg-white px-4 py-3 text-center transition hover:border-emerald-400" data-value="weekly">
                        <input type="radio" name="recurring_frequency" value="weekly" class="sr-only" @checked($recurringTransaction->frequency === 'weekly')>
                        <span class="block text-sm font-semibold text-gray-900">Mingguan</span>
                        <span class="text-xs text-gray-500">Setiap minggu</span>
                    </label>
                    <label class="frequency-option cursor-pointer rounded-2xl border-2 border-gray-200 bg-white px-4 py-3 text-center transition hover:border-emerald-400" data-value="monthly">
                        <input type="radio" name="recurring_frequency" value="monthly" class="sr-only" @checked($recurringTransaction->frequency === 'monthly')>
                        <span class="block text-sm font-semibold text-gray-900">Bulanan</span>
                        <span class="text-xs text-gray-500">Setiap bulan</span>
                    </label>
                    <label class="frequency-option cursor-pointer rounded-2xl border-2 border-gray-200 bg-white px-4 py-3 text-center transition hover:border-emerald-400" data-value="yearly">
                        <input type="radio" name="recurring_frequency" value="yearly" class="sr-only" @checked($recurringTransaction->frequency === 'yearly')>
                        <span class="block text-sm font-semibold text-gray-900">Tahunan</span>
                        <span class="text-xs text-gray-500">Setiap tahun</span>
                    </label>
                </div>
                @error('recurring_frequency')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Judul Transaksi</label>
                <input type="text" name="title" id="title" value="{{ old('title', $recurringTransaction->title) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition @error('title') border-red-500 @enderror" required placeholder="Contoh: Langganan Netflix">
                @error('title')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="amount" class="block text-sm font-semibold text-gray-700 mb-2">Nominal (Rp)</label>
                <input type="text" name="amount" id="amount" value="{{ old('amount', $recurringTransaction->amount) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition @error('amount') border-red-500 @enderror" required inputmode="numeric" pattern="[0-9]*" placeholder="Contoh: 120000">
                @error('amount')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">Mulai Tanggal</label>
                <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $recurringTransaction->start_date->format('Y-m-d')) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition @error('start_date') border-red-500 @enderror" required>
                @error('start_date')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Catatan (Opsional)</label>
                <textarea name="description" id="description" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition @error('description') border-red-500 @enderror" placeholder="Contoh: Langganan Netflix setiap bulan">{{ old('description', $recurringTransaction->description) }}</textarea>
                @error('description')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex gap-3 border-t border-gray-200 pt-6">
                <a href="{{ route('transactions.index') }}" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium text-center">
                    Batal
                </a>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const frequencySelector = document.getElementById('frequency-selector');
    const options = frequencySelector.querySelectorAll('.frequency-option');
    const radioInputs = frequencySelector.querySelectorAll('input[name="recurring_frequency"]');

    function updateStyle() {
        options.forEach(option => {
            const radio = option.querySelector('input[type="radio"]');
            if (radio.checked) {
                option.classList.add('border-emerald-500', 'bg-emerald-50');
                option.classList.remove('border-gray-200', 'bg-white');
            } else {
                option.classList.remove('border-emerald-500', 'bg-emerald-50');
                option.classList.add('border-gray-200', 'bg-white');
            }
        });
    }

    options.forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            updateStyle();
        });
    });

    updateStyle();
});
</script>
