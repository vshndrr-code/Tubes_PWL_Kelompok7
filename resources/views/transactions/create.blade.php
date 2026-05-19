@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-br from-blue-50 to-indigo-50 min-h-screen">
    <div class="max-w-2xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Tambah Transaksi Baru</h1>
            <p class="text-gray-500 mt-1">Catat transaksi keuangan Anda dengan mudah</p>
        </div>

        <!-- Form Card -->
        <form action="{{ route('transactions.store') }}" method="POST" class="bg-white rounded-2xl shadow-lg p-8">
            @csrf

            <!-- Wallet/Account -->
            <div class="mb-6">
                <label for="account_id" class="block text-sm font-semibold text-gray-700 mb-3">
                    Akun/Dompet
                </label>
                <select name="account_id" id="account_id" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors @error('account_id') border-red-500 @enderror" required>
                    <option value="">Pilih Akun</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" @selected(old('account_id') == $account->id)>
                            {{ $account->name }}
                        </option>
                    @endforeach
                </select>
                @error('account_id')
                    <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Category -->
            <div class="mb-6">
                <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-3">
                    Kategori
                </label>
                <select name="category_id" id="category_id" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors @error('category_id') border-red-500 @enderror" required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                            {{ $category->name }} ({{ ucfirst($category->type) }})
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-3">
                    Judul Transaksi
                </label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors @error('title') border-red-500 @enderror" required placeholder="Contoh: Makan siang, Bensin, Gaji, dll">
                @error('title')
                    <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Amount and Date Row -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="amount" class="block text-sm font-semibold text-gray-700 mb-3">
                        Jumlah
                    </label>
                    <input type="number" name="amount" id="amount" step="0.01" value="{{ old('amount', 0) }}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors @error('amount') border-red-500 @enderror" required placeholder="0">
                    @error('amount')
                        <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="transaction_date" class="block text-sm font-semibold text-gray-700 mb-3">
                        Tanggal
                    </label>
                    <input type="date" name="transaction_date" id="transaction_date" value="{{ old('transaction_date', now()->format('Y-m-d')) }}" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors @error('transaction_date') border-red-500 @enderror" required>
                    @error('transaction_date')
                        <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Note/Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-3">
                    Catatan (Opsional)
                </label>
                <textarea name="description" id="description" rows="3" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors @error('description') border-red-500 @enderror" placeholder="Tambahkan catatan atau detail transaksi">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
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
                            <label class="flex items-center">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                                    class="w-4 h-4 rounded border-gray-300 text-blue-600 cursor-pointer" 
                                    @checked(in_array($tag->id, old('tags', [])))
                                >
                                <span class="ml-2 text-sm text-gray-700 cursor-pointer">{{ $tag->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('tags')
                        <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                    @enderror
                </div>
            @endif

            <!-- Buttons -->
            <div class="flex gap-3 border-t pt-6">
                <a href="{{ route('transactions.index') }}" class="px-6 py-3 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300 transition-colors font-semibold">
                    Batal
                </a>
                <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl hover:shadow-lg transition-all duration-200 font-semibold">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
