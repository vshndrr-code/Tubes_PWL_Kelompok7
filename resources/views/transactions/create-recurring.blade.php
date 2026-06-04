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
        .ui-button:hover {
            transform: translateY(-2px);
        }
    }
</style>
@endpush

@section('content')
    <div class="min-h-screen bg-[#f6f7f9] text-slate-900">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-10 lg:px-8">
            @php
                $frequencyOptions = [
                    'daily' => [
                        'label' => 'Harian',
                        'description' => 'Setiap hari',
                        'icon' => '<path d="M8 2v4"/><path d="M16 2v4"/><rect x="3" y="4" width="18" height="18" rx="3"/><path d="M3 10h18"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/>',
                    ],
                    'weekly' => [
                        'label' => 'Mingguan',
                        'description' => 'Setiap minggu',
                        'icon' => '<path d="M8 2v4"/><path d="M16 2v4"/><rect x="3" y="4" width="18" height="18" rx="3"/><path d="M3 10h18"/><path d="M7 15h10"/>',
                    ],
                    'monthly' => [
                        'label' => 'Bulanan',
                        'description' => 'Setiap bulan',
                        'icon' => '<path d="M8 2v4"/><path d="M16 2v4"/><rect x="3" y="4" width="18" height="18" rx="3"/><path d="M3 10h18"/><path d="M9 16h6"/><path d="M12 13v6"/>',
                    ],
                    'yearly' => [
                        'label' => 'Tahunan',
                        'description' => 'Setiap tahun',
                        'icon' => '<path d="M8 2v4"/><path d="M16 2v4"/><rect x="3" y="4" width="18" height="18" rx="3"/><path d="M3 10h18"/><path d="M12 14v5"/><path d="m9.5 16.5 2.5-2.5 2.5 2.5"/>',
                    ],
                ];

                $selectedFrequency = old('recurring_frequency', 'monthly');
            @endphp

            <div class="mb-7 flex flex-col gap-4 border-b border-slate-200 pb-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Transaksi Berulang</p>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Tambah Jadwal Transaksi</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                        Buat transaksi otomatis untuk pengeluaran atau tagihan yang berulang.
                    </p>
                </div>

                <a href="{{ route('transactions.index') }}"
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
                <form action="{{ route('transactions.storeRecurring') }}" method="POST"
                    class="ui-reveal rounded-lg border border-slate-200 bg-white shadow-sm">
                    @csrf

                    <div class="border-b border-slate-200 px-5 py-4 sm:px-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Detail Jadwal</p>
                        <h2 class="mt-1 text-lg font-semibold text-slate-950">Informasi transaksi</h2>
                    </div>

                    <div class="space-y-6 p-5 sm:p-6">
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Pilih Periode</label>
                            <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                @foreach ($frequencyOptions as $value => $option)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="recurring_frequency" value="{{ $value }}"
                                            class="peer sr-only" @checked($selectedFrequency === $value)>
                                        <div class="ui-card rounded-lg border border-slate-200 bg-white p-4 shadow-sm ring-1 ring-transparent hover:border-slate-300 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:ring-emerald-100">
                                            <div class="flex items-start gap-3">
                                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-50 text-slate-500 ring-1 ring-slate-200 peer-checked:bg-emerald-100">
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                        {!! $option['icon'] !!}
                                                    </svg>
                                                </span>
                                                <span>
                                                    <span class="block text-sm font-semibold text-slate-950">{{ $option['label'] }}</span>
                                                    <span class="mt-1 block text-xs font-medium text-slate-500">{{ $option['description'] }}</span>
                                                </span>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('recurring_frequency')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label for="title" class="text-sm font-semibold text-slate-700">Judul Transaksi</label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}"
                                    class="mt-2 h-11 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100 @error('title') border-red-400 ring-red-100 @enderror"
                                    required placeholder="Contoh: Langganan Netflix">
                                @error('title')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="amount" class="text-sm font-semibold text-slate-700">Nominal</label>
                                <div class="mt-2 flex h-11 overflow-hidden rounded-lg border border-slate-200 bg-slate-50 shadow-sm transition focus-within:border-slate-400 focus-within:bg-white focus-within:ring-2 focus-within:ring-slate-100 @error('amount') border-red-400 ring-red-100 @enderror">
                                    <span class="flex items-center border-r border-slate-200 px-3 text-sm font-semibold text-slate-500">Rp</span>
                                    <input type="text" name="amount" id="amount" value="{{ old('amount') ?? '' }}"
                                        class="h-full w-full border-0 bg-transparent px-3 text-sm text-slate-700 outline-none focus:ring-0"
                                        required inputmode="numeric" pattern="[0-9]*" placeholder="120000">
                                </div>
                                @error('amount')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="start_date" class="text-sm font-semibold text-slate-700">Mulai Tanggal</label>
                                <input type="date" name="start_date" id="start_date" value="{{ old('start_date', now()->format('Y-m-d')) }}"
                                    class="mt-2 h-11 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100 @error('start_date') border-red-400 ring-red-100 @enderror"
                                    required>
                                @error('start_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="description" class="text-sm font-semibold text-slate-700">Catatan</label>
                                <textarea name="description" id="description" rows="4"
                                    class="mt-2 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100 @error('description') border-red-400 ring-red-100 @enderror"
                                    placeholder="Contoh: Langganan Netflix setiap bulan">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 border-t border-slate-200 px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
                        <a href="{{ route('transactions.index') }}"
                            class="ui-button inline-flex h-11 items-center justify-center rounded-lg border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-700 shadow-sm hover:border-slate-300 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300">
                            Batal
                        </a>
                        <button type="submit"
                            class="ui-button inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-emerald-600 px-5 text-sm font-semibold text-white shadow-sm shadow-emerald-700/15 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Simpan Jadwal
                        </button>
                    </div>
                </form>

                <aside class="space-y-5">
                    <section class="ui-card overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
                        <div class="bg-slate-950 p-5 text-white">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Ringkasan</p>
                            <h3 class="mt-2 text-xl font-semibold">Jadwal berulang</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-300">
                                Transaksi akan dibuat mengikuti periode yang dipilih.
                            </p>
                        </div>

                        <div class="space-y-3 p-5">
                            <div class="rounded-lg bg-slate-50 p-4 ring-1 ring-slate-200">
                                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Default</p>
                                <p class="mt-2 text-sm font-semibold text-slate-950">
                                    {{ $frequencyOptions[$selectedFrequency]['label'] ?? 'Bulanan' }}
                                </p>
                            </div>

                            <div class="rounded-lg bg-emerald-50 p-4 ring-1 ring-emerald-100">
                                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-emerald-700">Status</p>
                                <p class="mt-2 text-sm font-semibold text-emerald-800">Siap dijadwalkan</p>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Alur</p>
                        <div class="mt-4 space-y-4">
                            <div class="flex gap-3">
                                <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-slate-950 text-xs font-bold text-white">1</span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-950">Pilih periode</p>
                                    <p class="mt-1 text-sm text-slate-500">Harian, mingguan, bulanan, atau tahunan.</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-slate-950 text-xs font-bold text-white">2</span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-950">Isi nominal</p>
                                    <p class="mt-1 text-sm text-slate-500">Gunakan angka tanpa titik atau koma.</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-slate-950 text-xs font-bold text-white">3</span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-950">Simpan jadwal</p>
                                    <p class="mt-1 text-sm text-slate-500">Jadwal muncul di daftar transaksi berulang.</p>
                                </div>
                            </div>
                        </div>
                    </section>
                </aside>
            </div>
        </div>
    </div>
@endsection
