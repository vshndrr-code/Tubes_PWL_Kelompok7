@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f6f7f9]">
    <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 sm:py-10 lg:px-8">
        <!-- Header -->
        <div class="mb-8 border-b border-slate-200 pb-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Detail Budget</p>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                        {{ $budgeting->category->name ?? '-' }}
                    </h1>
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Kelola budget untuk {{ ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][$budgeting->month] ?? '-' }} {{ $budgeting->year }}
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('budgetings.index') }}"
                        class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-slate-200 hover:bg-slate-50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali
                    </a>
                    <a href="{{ route('budgetings.edit', $budgeting->id) }}"
                        class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-amber-600 px-4 text-sm font-semibold text-white shadow-sm shadow-amber-700/15 hover:bg-amber-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Left Column: Primary Info -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Budget Limit Card -->
                <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Limit Budget</p>
                    <div class="mt-4 flex items-baseline gap-2">
                        <p class="text-4xl font-bold text-slate-950">Rp</p>
                        <p class="text-4xl font-bold text-emerald-600">{{ number_format($budgeting->limit_amount, 0, ',', '.') }}</p>
                    </div>
                    <p class="mt-3 text-sm text-slate-600">
                        Batas maksimal pengeluaran yang telah Anda tentukan untuk kategori ini
                    </p>
                </div>

                <!-- Period Info -->
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Bulan</p>
                        <p class="mt-3 text-lg font-semibold text-slate-900">
                            {{ ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][$budgeting->month] ?? '-' }}
                        </p>
                    </div>
                    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Tahun</p>
                        <p class="mt-3 text-lg font-semibold text-slate-900">{{ $budgeting->year }}</p>
                    </div>
                </div>

                <!-- Category Info -->
                <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Kategori</p>
                    <div class="mt-4 flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 19V5" />
                                <path d="M8 17V9" />
                                <path d="M12 15V7" />
                                <path d="M16 19v-6" />
                                <path d="M20 17V4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-slate-600">Kategori Pengeluaran</p>
                            <p class="text-lg font-semibold text-slate-900">{{ $budgeting->category->name ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Timeline & Info -->
            <div class="space-y-6">
                <!-- Created & Updated Info -->
                <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Informasi Sistem</p>
                    <div class="mt-4 space-y-4 text-sm">
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-[0.1em]">Dibuat Pada</p>
                            <p class="mt-1 font-medium text-slate-900">
                                {{ optional($budgeting->created_at)->format('d M Y') ?? '-' }}
                            </p>
                            <p class="text-xs text-slate-500">
                                {{ optional($budgeting->created_at)->format('H:i') ?? '-' }}
                            </p>
                        </div>
                        <div class="border-t border-slate-100 pt-4">
                            <p class="text-xs text-slate-500 uppercase tracking-[0.1em]">Terakhir Diperbarui</p>
                            <p class="mt-1 font-medium text-slate-900">
                                {{ optional($budgeting->updated_at)->format('d M Y') ?? '-' }}
                            </p>
                            <p class="text-xs text-slate-500">
                                {{ optional($budgeting->updated_at)->format('H:i') ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700">Status</p>
                    <div class="mt-4 flex items-center gap-2">
                        <span class="inline-block h-3 w-3 rounded-full bg-emerald-500"></span>
                        <p class="text-sm font-semibold text-emerald-900">Aktif</p>
                    </div>
                    <p class="mt-2 text-xs leading-5 text-emerald-700">
                        Budget ini sedang aktif dan digunakan untuk tracking pengeluaran Anda.
                    </p>
                </div>

                <!-- Quick Actions -->
                <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 mb-4">Aksi Cepat</p>
                    <div class="space-y-2">
                        <a href="{{ route('budgetings.edit', $budgeting->id) }}"
                            class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Budget
                        </a>
                        <form action="{{ route('budgetings.destroy', $budgeting->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus budget ini?');" class="inline-block w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus Budget
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Info Card -->
        <div class="mt-8 rounded-lg border border-blue-200 bg-blue-50 p-6">
            <div class="flex gap-4">
                <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-700">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-blue-900">Cara Menggunakan Budget</h3>
                    <ul class="mt-2 space-y-1 text-xs text-blue-700">
                        <li>• Budget ini membantu Anda melacak pengeluaran kategori ini</li>
                        <li>• Setiap transaksi yang Anda input akan dikurangkan dari limit</li>
                        <li>• Anda akan mendapat notifikasi jika mendekati atau melampaui limit</li>
                        <li>• Edit atau hapus budget kapan saja sesuai kebutuhan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
