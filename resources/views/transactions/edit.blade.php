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
    <div class="min-h-screen bg-[#f6f7f9] text-slate-900">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-10 lg:px-8">
            @php
                $isIncome = $transaction->type === 'income';
            @endphp

            <div class="mb-7 flex flex-col gap-4 border-b border-slate-200 pb-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Edit Aktivitas</p>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Edit Transaksi</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                        Perbarui detail transaksi tanpa mengubah pola pencatatan yang sudah ada.
                    </p>
                </div>

                <a href="{{ route('transactions.show', $transaction) }}"
                    class="ui-button inline-flex h-11 items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm hover:border-slate-300 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300 focus:ring-offset-2 focus:ring-offset-[#f6f7f9]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5" />
                        <path d="m12 19-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
            </div>

            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_360px]">
                <form id="transaction-form" action="{{ route('transactions.update', $transaction) }}" method="POST"
                    class="ui-reveal rounded-lg border border-slate-200 bg-white shadow-sm">
                    @csrf
                    @method('PUT')

                    <div class="border-b border-slate-200 px-5 py-4 sm:px-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Form Transaksi</p>
                        <h2 class="mt-1 text-lg font-semibold text-slate-950">Perubahan transaksi</h2>
                    </div>

                    <div class="space-y-6 p-5 sm:p-6">
                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label for="account_id" class="text-sm font-semibold text-slate-700">Akun/Dompet</label>
                                <select name="account_id" id="account_id"
                                    class="mt-2 h-11 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100 @error('account_id') border-red-400 ring-red-100 @enderror"
                                    required>
                                    <option value="">Pilih Akun</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}" @selected(old('account_id', $transaction->account_id) == $account->id)>
                                            {{ $account->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('account_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="transaction_date" class="text-sm font-semibold text-slate-700">Tanggal</label>
                                <input type="date" name="transaction_date" id="transaction_date"
                                    value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}"
                                    class="mt-2 h-11 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100 @error('transaction_date') border-red-400 ring-red-100 @enderror"
                                    required>
                                @error('transaction_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-slate-700">Kategori</label>
                            <div class="mt-2 rounded-lg border border-slate-200 bg-slate-50 p-3">
                                <x-category-selector :categories="$categories" :selected-category-id="old('category_id', $transaction->category_id)" />
                            </div>
                            @error('category_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <input type="hidden" name="type" id="type" value="{{ old('type', optional($transaction->category)->type ?? $transaction->type) }}">

                        <div>
                            <label for="title" class="text-sm font-semibold text-slate-700">Judul Transaksi</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $transaction->title) }}"
                                class="mt-2 h-11 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100 @error('title') border-red-400 ring-red-100 @enderror"
                                required placeholder="Masukkan judul transaksi">
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="amount" class="text-sm font-semibold text-slate-700">Jumlah</label>
                            <div class="mt-2 flex h-11 overflow-hidden rounded-lg border border-slate-200 bg-slate-50 shadow-sm transition focus-within:border-slate-400 focus-within:bg-white focus-within:ring-2 focus-within:ring-slate-100 @error('amount') border-red-400 ring-red-100 @enderror">
                                <span class="flex items-center border-r border-slate-200 px-3 text-sm font-semibold text-slate-500">Rp</span>
                                <input type="text" name="amount" id="amount" value="{{ old('amount') ?? $transaction->amount }}"
                                    class="h-full w-full border-0 bg-transparent px-3 text-sm text-slate-700 outline-none focus:ring-0"
                                    required inputmode="numeric" pattern="[0-9]*">
                            </div>
                            @error('amount')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="text-sm font-semibold text-slate-700">Catatan</label>
                            <textarea name="description" id="description" rows="4"
                                class="mt-2 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100 @error('description') border-red-400 ring-red-100 @enderror"
                                placeholder="Tambahkan catatan atau detail transaksi">{{ old('description', $transaction->description) }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        @if ($tags->count() > 0)
                            <div>
                                <label class="text-sm font-semibold text-slate-700">Tag</label>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach ($tags as $tag)
                                        <label class="inline-flex cursor-pointer items-center rounded-md bg-slate-50 px-3 py-2 text-sm font-medium text-slate-700 ring-1 ring-slate-200 hover:bg-white">
                                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                                class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                                @checked($transaction->tags->contains($tag->id))>
                                            <span class="ml-2">{{ $tag->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('tags')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col gap-3 border-t border-slate-200 px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
                        <a href="{{ route('transactions.show', $transaction) }}"
                            class="ui-button inline-flex h-11 items-center justify-center rounded-lg border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-700 shadow-sm hover:border-slate-300 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300">
                            Batal
                        </a>
                        <button type="submit"
                            class="ui-button inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-slate-950 px-5 text-sm font-semibold text-white shadow-sm shadow-slate-900/10 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400">
                            Perbarui Transaksi
                        </button>
                    </div>
                </form>

                <aside class="space-y-5">
                    <section class="ui-card overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
                        <div class="bg-slate-950 p-5 text-white">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Transaksi Saat Ini</p>
                            <h3 class="mt-2 text-xl font-semibold">{{ $transaction->title }}</h3>
                            <p class="mt-3 text-3xl font-bold {{ $isIncome ? 'text-emerald-300' : 'text-rose-300' }}">
                                {{ $isIncome ? '+Rp' : '-Rp' }}{{ number_format($transaction->amount, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="space-y-3 p-5">
                            <div class="rounded-lg bg-slate-50 p-4 ring-1 ring-slate-200">
                                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Akun</p>
                                <p class="mt-2 text-sm font-semibold text-slate-950">{{ optional($transaction->account)->name ?? '-' }}</p>
                            </div>
                            <div class="rounded-lg bg-slate-50 p-4 ring-1 ring-slate-200">
                                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Kategori</p>
                                <p class="mt-2 text-sm font-semibold text-slate-950">{{ optional($transaction->category)->name ?? '-' }}</p>
                            </div>
                        </div>
                    </section>
                </aside>
            </div>
        </div>
    </div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categories = @json($categories);
    const typeInput = document.getElementById('type');
    const form = document.getElementById('transaction-form');

    if (!form || !typeInput) {
        return;
    }

    form.addEventListener('submit', function() {
        const categoryInputs = form.querySelectorAll('input[name="category_id"]');
        let categoryIdValue = null;

        for (let input of categoryInputs) {
            if (input.value) {
                categoryIdValue = input.value;
                break;
            }
        }

        if (categoryIdValue) {
            const selectedCategory = categories.find(c => c.id == parseInt(categoryIdValue));
            if (selectedCategory) {
                typeInput.value = selectedCategory.type;
            }
        }
    });
});
</script>
