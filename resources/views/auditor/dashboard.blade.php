@extends('layouts.app')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Dashboard Auditor</h1>
            <p class="text-sm text-slate-500 mt-1">Financial & Content Quality Assurance System</p>
        </div>
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl px-4 py-2 flex items-center gap-2">
            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-xs font-semibold text-emerald-800">Mode Auditor Aktif</span>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <!-- Card 1: Users -->
        <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm hover:shadow-md transition duration-200 flex items-center gap-5">
            <div class="p-4 bg-blue-50 text-blue-600 rounded-2xl">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Masyarakat</p>
                <p class="text-3xl font-extrabold text-slate-800 mt-1">{{ number_format($totalUsers, 0, ',', '.') }}</p>
                <p class="text-xs text-slate-500 mt-1">Pengguna aktif terdaftar</p>
            </div>
        </div>

        <!-- Card 2: Transactions -->
        <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm hover:shadow-md transition duration-200 flex items-center gap-5">
            <div class="p-4 bg-amber-50 text-amber-600 rounded-2xl">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Transaksi</p>
                <p class="text-3xl font-extrabold text-slate-800 mt-1">{{ number_format($totalTransactions, 0, ',', '.') }}</p>
                <p class="text-xs text-slate-500 mt-1">Transaksi tercatat di sistem</p>
            </div>
        </div>

        <!-- Card 3: Savings Goals -->
        <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm hover:shadow-md transition duration-200 flex items-center gap-5">
            <div class="p-4 bg-emerald-50 text-emerald-600 rounded-2xl">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M12 16v1M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tabungan Global</p>
                <p class="text-3xl font-extrabold text-slate-800 mt-1">Rp {{ number_format($globalSavings, 0, ',', '.') }}</p>
                <p class="text-xs text-slate-500 mt-1">Total akumulasi tabungan saat ini</p>
            </div>
        </div>
    </div>

    <!-- Welcome / Info Widget -->
    <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2">
            <h2 class="text-2xl font-bold text-slate-800">Selamat datang kembali, Auditor!</h2>
            <p class="text-sm text-slate-500 max-w-xl">
                Gunakan panel navigasi di sebelah kiri untuk melakukan moderasi terhadap Kategori Global SDGs dan memantau konten tag yang dibuat oleh pengguna.
            </p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('auditor.categories.index') }}"
                class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 px-5 rounded-2xl shadow-sm transition duration-150 text-sm">
                Moderasi Kategori
            </a>
            <a href="{{ route('auditor.tags.index') }}"
                class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-3 px-5 rounded-2xl transition duration-150 text-sm">
                Moderasi Tag
            </a>
        </div>
    </div>
</div>
@endsection
