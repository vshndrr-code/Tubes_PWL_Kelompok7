@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen">
    <div class="max-w-2xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Edit Transaksi</h1>
            <p class="text-gray-500 mt-1">Ubah detail transaksi keuangan Anda</p>
        </div>

        <!-- Form Card -->
        <form action="{{ route('transactions.update', $transaction) }}" method="POST" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
            @csrf
            @method('PUT')

            <!-- Wallet/Account -->
            <div class="mb-6">
                <label for="account_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    Akun/Dompet
                </label>
                <select name="account_id" id="account_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition @error('account_id') border-red-500 @enderror" required>
                    <option value="">Pilih Akun</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" @selected($transaction->account_id == $account->id)>
                            {{ $account->name }}
                        </option>
                    @endforeach
                </select>
                @error('account_id')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Category Selector -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Kategori
                </label>
                <x-category-selector :categories="$categories" :selected-category-id="old('category_id', $transaction->category_id)" />
                @error('category_id')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                    Judul Transaksi
                </label>
                <input type="text" name="title" id="title" value="{{ old('title', $transaction->title) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition @error('title') border-red-500 @enderror" required placeholder="Masukkan judul transaksi">
                @error('title')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Amount and Date Row -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="amount" class="block text-sm font-semibold text-gray-700 mb-2">
                        Jumlah (Rp)
                    </label>
                    <input type="number" name="amount" id="amount" step="0.01" value="{{ old('amount', $transaction->amount) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition @error('amount') border-red-500 @enderror" required>
                    @error('amount')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="transaction_date" class="block text-sm font-semibold text-gray-700 mb-2">
                        Tanggal
                    </label>
                    <input type="date" name="transaction_date" id="transaction_date" value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition @error('transaction_date') border-red-500 @enderror" required>
                    @error('transaction_date')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Note/Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                    Catatan (Opsional)
                </label>
                <textarea name="description" id="description" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition @error('description') border-red-500 @enderror" placeholder="Tambahkan catatan atau detail transaksi">{{ old('description', $transaction->description) }}</textarea>
                @error('description')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tags -->
            @if($tags->count() > 0)
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        Tag (Opsional)
                    </label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($tags as $tag)
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                                    class="w-4 h-4 rounded border-gray-300 text-blue-600" 
                                    @checked($transaction->tags->contains($tag->id))
                                >
                                <span class="ml-2 text-sm text-gray-700">{{ $tag->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('tags')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            @endif

            <!-- Buttons -->
            <div class="flex gap-3 border-t border-gray-200 pt-6">
                <a href="{{ route('transactions.show', $transaction) }}" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium text-center">
                    Batal
                </a>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    Perbarui Transaksi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
