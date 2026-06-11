@extends('layouts.app')

@push('head')
<style>
    [x-cloak] { display: none !important; }

    @media (prefers-reduced-motion: no-preference) {
        .form-input {
            transition: border-color .18s ease, background-color .18s ease, box-shadow .18s ease;
        }
        .form-input:focus {
            transform: translateY(-1px);
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-[#f6f7f9]">
    <div class="mx-auto max-w-2xl px-4 py-6 sm:px-6 sm:py-10 lg:px-8">
        <!-- Header -->
        <div class="mb-8 border-b border-slate-200 pb-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Manajemen Budget</p>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Buat Budget Baru</h1>
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Tentukan limit pengeluaran untuk kategori dan periode tertentu guna membantu Anda mengelola keuangan dengan lebih baik.
                    </p>
                </div>
                <a href="{{ route('budgetings.index') }}"
                    class="inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-slate-200 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <form action="{{ route('budgetings.store') }}" method="POST" class="space-y-0">
                @csrf
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                <div class="space-y-6 px-6 py-8 sm:px-8 sm:py-10">
                    <!-- Nama Budget -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700">
                            Nama Budget
                            <span class="text-red-600">*</span>
                        </label>
                        <p class="mt-1 text-xs text-slate-500">Nama unik untuk budget ini (contoh: Makan Siang Bulanan)</p>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required
                            class="form-input mt-3 block w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm outline-none focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20"
                            placeholder="Contoh: Belanja Bulanan" />
                        @error('name')
                        <p class="mt-2 text-xs text-red-600 flex items-center gap-1">
                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700">
                            Kategori (Opsional)
                        </label>
                        <p class="mt-1 text-xs text-slate-500">Batasi budget ini hanya untuk kategori tertentu saja (opsional)</p>
                        <div class="mt-3">
                            <x-category-selector :categories="$categories" :selected-category-id="old('category_id', request('category'))" :allow-null="true" />
                        </div>
                        @error('category_id')
                        <p class="mt-2 text-xs text-red-600 flex items-center gap-1">
                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Jumlah Limit -->
                    <div>
                        <label for="limit_amount" class="block text-sm font-semibold text-slate-700">
                            Jumlah Limit
                            <span class="text-red-600">*</span>
                        </label>
                        <p class="mt-1 text-xs text-slate-500">Maksimal pengeluaran untuk kategori ini dalam periode yang ditentukan</p>
                        <div class="relative mt-3">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-semibold text-slate-500">{{ $currencySymbol }}</span>
                            <input id="limit_amount" name="limit_amount" type="number" inputmode="numeric" step="1" min="0" value="{{ old('limit_amount') }}" required
                                class="form-input block w-full rounded-lg border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-700 shadow-sm outline-none focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20"
                                placeholder="0" />
                        </div>
                        @error('limit_amount')
                        <p class="mt-2 text-xs text-red-600 flex items-center gap-1">
                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Periode (Bulan & Tahun) -->
                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label for="month" class="block text-sm font-semibold text-slate-700">
                                Bulan
                                <span class="text-red-600">*</span>
                            </label>
                            <p class="mt-1 text-xs text-slate-500">Periode bulan budget ini berlaku</p>
                            <select id="month" name="month" required
                                class="form-input mt-3 block w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm outline-none focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20">
                                <option value="">-- Pilih bulan --</option>
                                @php
                                $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                @endphp
                                @foreach(range(1, 12) as $month)
                                <option value="{{ $month }}" {{ old('month') == $month ? 'selected' : '' }}>
                                    {{ $months[$month - 1] }}
                                </option>
                                @endforeach
                            </select>
                            @error('month')
                            <p class="mt-2 text-xs text-red-600 flex items-center gap-1">
                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <div>
                            <label for="year" class="block text-sm font-semibold text-slate-700">
                                Tahun
                                <span class="text-red-600">*</span>
                            </label>
                            <p class="mt-1 text-xs text-slate-500">Tahun periode budget ini berlaku</p>
                            <input id="year" name="year" type="number" value="{{ old('year', date('Y')) }}" required
                                class="form-input mt-3 block w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-sm outline-none focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20"
                                placeholder="2026" />
                            @error('year')
                            <p class="mt-2 text-xs text-red-600 flex items-center gap-1">
                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="rounded-lg bg-emerald-50 p-4 ring-1 ring-emerald-100">
                        <p class="text-xs font-semibold text-emerald-700 flex items-center gap-2">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                            Tips
                        </p>
                        <p class="mt-2 text-xs leading-5 text-emerald-700">
                            Tetapkan limit yang realistis berdasarkan kebiasaan pengeluaran Anda. Budget ini akan membantu Anda memantau dan mengontrol pengeluaran setiap bulan.
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4 sm:px-8">
                    <a href="{{ route('budgetings.index') }}"
                        class="inline-flex h-10 items-center justify-center rounded-lg bg-white px-4 text-sm font-semibold text-slate-700 ring-1 ring-slate-200 hover:bg-slate-100">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-emerald-600 px-6 text-sm font-semibold text-white shadow-sm shadow-emerald-700/15 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-[#f6f7f9]">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" /></svg>
                        Simpan Budget
                    </button>
                </div>
            </form>
        </div>

        <!-- Helpful Info -->
        <div class="mt-8 rounded-lg border border-slate-200 bg-white p-6">
            <h3 class="text-sm font-semibold text-slate-900">Bagaimana Cara Kerja Budget?</h3>
            <ul class="mt-4 space-y-3 text-sm text-slate-600">
                <li class="flex gap-3">
                    <span class="inline-flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 text-sm font-semibold text-emerald-700">1</span>
                    <span>Tentukan kategori dan limit pengeluaran untuk periode tertentu</span>
                </li>
                <li class="flex gap-3">
                    <span class="inline-flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 text-sm font-semibold text-emerald-700">2</span>
                    <span>Setiap transaksi yang Anda input akan dihitung otomatis</span>
                </li>
                <li class="flex gap-3">
                    <span class="inline-flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 text-sm font-semibold text-emerald-700">3</span>
                    <span>Pantau sisa budget Anda melalui dashboard budgeting</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
