@extends('layouts.app')

@push('head')
<style>
    @keyframes soft-enter {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (prefers-reduced-motion: no-preference) {
        .ui-reveal {
            animation: soft-enter .42s ease-out both;
        }

        .ui-card {
            transition:
                transform .18s ease,
                box-shadow .18s ease,
                border-color .18s ease,
                background-color .18s ease,
                color .18s ease;
        }

        .ui-card:hover {
            transform: translateY(-2px);
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-[#f6f7f9] text-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-10 lg:px-8">
        
        <!-- Header -->
        <div class="mb-7 flex flex-col gap-4 border-b border-slate-200 pb-6 lg:flex-row lg:items-end lg:justify-between ui-reveal">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Overview Panel</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Dashboard Auditor</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                    Sistem pemantauan keuangan, integrasi kategori global SDGs, dan moderasi konten transaksi.
                </p>
            </div>
            <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-200/60 rounded-full px-4 py-2 self-start lg:self-auto shadow-sm">
                <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-xs font-semibold text-emerald-800">Sistem Keamanan Aktif</span>
            </div>
        </div>

        <!-- Alert Success -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-xl shadow-sm flex items-center justify-between ui-reveal">
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Premium Dark Overview Card -->
        <div class="ui-reveal mb-8 block overflow-hidden rounded-3xl bg-slate-950 p-6 text-white shadow-lg shadow-slate-900/10 transition duration-150">
            <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Kategori Global SDGs</p>
                    <p class="mt-3 text-3xl font-bold tracking-tight sm:text-5xl">
                        {{ number_format($globalCategoriesCount, 0, ',', '.') }}
                        <span class="text-lg font-medium text-slate-400 ml-1">kategori aktif</span>
                    </p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/[0.06] px-4 py-3 self-start md:self-auto">
                    <p class="text-xs text-slate-400">Waktu Server</p>
                    <p class="mt-1 text-sm font-semibold text-white">
                        {{ now()->format('d M Y, H:i') }} WIB
                    </p>
                </div>
            </div>

            <!-- Stats Sub-metrics in dark card -->
            <div class="mt-7 grid gap-4 sm:grid-cols-2 md:grid-cols-4">
                <div class="rounded-2xl border border-white/10 bg-white/[0.06] p-5">
                    <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Total Masyarakat</p>
                    <p class="mt-2 text-2xl font-bold text-blue-300">
                        {{ number_format($totalUsers, 0, ',', '.') }}
                    </p>
                    <p class="text-[10px] text-slate-400 mt-1">Pengguna terdaftar</p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/[0.06] p-5">
                    <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Total Transaksi</p>
                    <p class="mt-2 text-2xl font-bold text-amber-300">
                        {{ number_format($totalTransactions, 0, ',', '.') }}
                    </p>
                    <p class="text-[10px] text-slate-400 mt-1">Tercatat di sistem</p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/[0.06] p-5">
                    <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Tabungan Tercapai</p>
                    <p class="mt-2 text-2xl font-bold text-emerald-300">
                        {{ number_format($totalAchievedSavings, 0, ',', '.') }}
                    </p>
                    <p class="text-[10px] text-slate-400 mt-1">Goal terpenuhi</p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/[0.06] p-5">
                    <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Kategori SDGs</p>
                    <p class="mt-2 text-2xl font-bold text-violet-300">
                        {{ number_format($globalCategoriesCount, 0, ',', '.') }}
                    </p>
                    <p class="text-[10px] text-slate-400 mt-1">Kategori global aktif</p>
                </div>
            </div>
        </div>

        <!-- Moderation Modules Grid (Clean, professional cards with lift effects) -->
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 ui-reveal">
            
            <!-- Module 1: Categories -->
            <div class="ui-card flex flex-col justify-between rounded-3xl border border-slate-200 bg-white p-6 shadow-sm hover:border-slate-300 hover:shadow-md transition">
                <div>
                    <div class="h-12 w-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Moderasi Kategori</h3>
                    <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                        Kelola data kategori global SDGs yang digunakan oleh seluruh masyarakat. Pisahkan, buat, dan bersihkan secara sistem-wide.
                    </p>
                </div>
                <div class="mt-6">
                    <a href="{{ route('auditor.categories.index') }}" 
                        class="inline-flex w-full items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700 transition">
                        <span>Buka Manajemen Kategori</span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Module 2: Tags -->
            <div class="ui-card flex flex-col justify-between rounded-3xl border border-slate-200 bg-white p-6 shadow-sm hover:border-slate-300 hover:shadow-md transition">
                <div>
                    <div class="h-12 w-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Moderasi Tag</h3>
                    <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                        Pantau seluruh hashtag transaksi buatan pengguna. Bersihkan tag yang tidak sesuai dengan pedoman konten sistem keuangan.
                    </p>
                </div>
                <div class="mt-6">
                    <a href="{{ route('auditor.tags.index') }}" 
                        class="inline-flex w-full items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 transition">
                        <span>Buka Moderasi Tag</span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Module 3: SDG Health status -->
            <div class="ui-card flex flex-col justify-between rounded-3xl border border-slate-200 bg-white p-6 shadow-sm hover:border-slate-300 hover:shadow-md transition sm:col-span-2 lg:col-span-1">
                <div>
                    <div class="h-12 w-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">SDGs Goal Health</h3>
                    <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                        Integrasi 17 target Pembangunan Berkelanjutan (SDGs) berjalan optimal. Kategori global terdistribusi merata ke seluruh masyarakat.
                    </p>
                </div>
                <div class="mt-6">
                    <div class="flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                            <span class="text-xs font-semibold text-slate-700">Database Connection</span>
                        </div>
                        <span class="text-xs font-mono text-slate-500">100% Online</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
