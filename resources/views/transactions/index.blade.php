@extends('layouts.app')

@push('head')
<style>
    [x-cloak] { display: none !important; }

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
                $transactionItems =
                    $transactions instanceof \Illuminate\Pagination\AbstractPaginator
                        ? $transactions->getCollection()
                        : collect($transactions);

                $recurringItems =
                    isset($recurringTransactions) && $recurringTransactions instanceof \Illuminate\Pagination\AbstractPaginator
                        ? $recurringTransactions->getCollection()
                        : collect($recurringTransactions ?? []);

                $totalIncome = $transactionItems->where('type', 'income')->sum('amount');
                $totalExpense = $transactionItems->where('type', 'expense')->sum('amount');
                $balance = $accountBalance ?? ($totalIncome - $totalExpense);
                $transactionCount = $transactionItems->count();
                $latestTransactionDate = optional($transactionItems->sortByDesc('transaction_date')->first())->transaction_date;
            @endphp

            <div x-data="{
                filter: 'all',
                search: '',
                transactions: @js($transactionItems->map(fn($item) => [
                    'type' => $item->type,
                    'title' => $item->title,
                    'account' => optional($item->account)->name,
                    'category' => optional($item->category)->name,
                    'date' => optional($item->transaction_date)->format('d M Y'),
                ])->values()),
                matches(type, title = '', account = '', category = '', date = '') {
                    const text = `${title} ${account ?? ''} ${category ?? ''} ${date ?? ''}`.toLowerCase();
                    const matchesType = this.filter === 'all' || this.filter === type;
                    const matchesSearch = this.search === '' || text.includes(this.search.toLowerCase());
                    return matchesType && matchesSearch;
                },
                hasMatches() {
                    return this.transactions.some(item => this.matches(item.type, item.title, item.account, item.category, item.date));
                }
            }">
                <div class="mb-7 flex flex-col gap-4 border-b border-slate-200 pb-6 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Pusat Transaksi</p>
                        <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Transaksi</h1>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                            Kelola pemasukan, pengeluaran, dan jadwal transaksi berulang dalam tampilan tabel.
                        </p>
                    </div>

                    <div class="flex w-full flex-col gap-2 sm:flex-row lg:w-auto">
                        <a href="{{ route('transactions.createRecurring') }}"
                            class="ui-button inline-flex h-11 items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm hover:border-slate-300 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300 focus:ring-offset-2 focus:ring-offset-[#f6f7f9]">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4v5h5" />
                                <path d="M20 20v-5h-5" />
                                <path d="M20 9a8 8 0 0 0-13.5-3.5L4 8" />
                                <path d="M4 15a8 8 0 0 0 13.5 3.5L20 16" />
                            </svg>
                            Transaksi Berulang
                        </a>

                        <a href="{{ route('transactions.create') }}"
                            class="ui-button inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 text-sm font-semibold text-white shadow-sm shadow-emerald-700/15 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-[#f6f7f9]">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Transaksi
                        </a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mb-6 rounded-lg border border-emerald-200 bg-white px-4 py-3 text-sm font-medium text-emerald-800 shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mb-5 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="ui-reveal rounded-lg bg-slate-950 p-5 text-white shadow-lg shadow-slate-900/10 md:col-span-2">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Saldo Saat Ini</p>
                                <p class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">
                                    {{ $balance < 0 ? '-Rp' : 'Rp' }}{{ number_format(abs($balance), 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="rounded-lg border border-white/10 bg-white/[0.06] px-4 py-3">
                                <p class="text-xs text-slate-400">Update terakhir</p>
                                <p class="mt-1 text-sm font-semibold text-white">
                                    {{ optional($latestTransactionDate)->format('d M Y') ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="ui-card rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Pemasukan</p>
                        <p class="mt-3 text-2xl font-bold text-emerald-700">
                            Rp{{ number_format($totalIncome, 0, ',', '.') }}
                        </p>
                        <p class="mt-1 text-sm text-slate-500">{{ $transactionItems->where('type', 'income')->count() }} transaksi</p>
                    </div>

                    <div class="ui-card rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Pengeluaran</p>
                        <p class="mt-3 text-2xl font-bold text-rose-700">
                            Rp{{ number_format($totalExpense, 0, ',', '.') }}
                        </p>
                        <p class="mt-1 text-sm text-slate-500">{{ $transactionItems->where('type', 'expense')->count() }} transaksi</p>
                    </div>
                </div>

                <div class="mb-5 rounded-lg border border-slate-200 bg-white p-3 shadow-sm">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <div class="relative w-full lg:max-w-md">
                            <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                            </svg>
                            <input
                                x-model="search"
                                type="search"
                                placeholder="Cari judul, akun, kategori, tanggal..."
                                class="h-10 w-full rounded-lg border border-slate-200 bg-slate-50 px-10 text-sm text-slate-700 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100"
                            />
                        </div>

                        <div class="flex flex-wrap gap-1 rounded-lg bg-slate-100 p-1">
                            <button type="button"
                                @click="filter = 'all'"
                                :class="filter === 'all' ? 'bg-white text-slate-950 shadow-sm' : 'text-slate-600 hover:text-slate-950'"
                                class="rounded-md px-3 py-2 text-xs font-semibold transition">
                                Semua
                            </button>
                            <button type="button"
                                @click="filter = 'income'"
                                :class="filter === 'income' ? 'bg-white text-slate-950 shadow-sm' : 'text-slate-600 hover:text-slate-950'"
                                class="rounded-md px-3 py-2 text-xs font-semibold transition">
                                Pemasukan
                            </button>
                            <button type="button"
                                @click="filter = 'expense'"
                                :class="filter === 'expense' ? 'bg-white text-slate-950 shadow-sm' : 'text-slate-600 hover:text-slate-950'"
                                class="rounded-md px-3 py-2 text-xs font-semibold transition">
                                Pengeluaran
                            </button>
                        </div>
                    </div>
                </div>

                @if ($recurringItems->isNotEmpty())
                    <section class="mb-6 rounded-lg border border-slate-200 bg-white shadow-sm">
                        <div class="flex flex-col gap-3 border-b border-slate-200 px-4 py-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Jadwal Aktif</p>
                                <h2 class="mt-1 text-lg font-semibold text-slate-950">Transaksi berulang</h2>
                            </div>

                            <span class="inline-flex w-fit rounded-md bg-amber-50 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] text-amber-700 ring-1 ring-amber-100">
                                {{ $recurringItems->count() }} aktif
                            </span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                                <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">
                                    <tr>
                                        <th scope="col" class="px-4 py-3">Nama</th>
                                        <th scope="col" class="px-4 py-3">Frekuensi</th>
                                        <th scope="col" class="px-4 py-3">Akun</th>
                                        <th scope="col" class="px-4 py-3">Mulai</th>
                                        <th scope="col" class="px-4 py-3 text-right">Nominal</th>
                                        <th scope="col" class="px-4 py-3 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @foreach ($recurringItems as $recurring)
                                        <tr class="hover:bg-slate-50/70">
                                            <td class="px-4 py-4">
                                                <div class="flex items-center gap-3">
                                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-amber-700 ring-1 ring-amber-100">
                                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M4 4v5h5" />
                                                            <path d="M20 20v-5h-5" />
                                                            <path d="M20 9a8 8 0 0 0-13.5-3.5L4 8" />
                                                            <path d="M4 15a8 8 0 0 0 13.5 3.5L20 16" />
                                                        </svg>
                                                    </span>
                                                    <div class="min-w-0">
                                                        <p class="font-semibold text-slate-950">{{ $recurring->title }}</p>
                                                        <p class="mt-1 text-xs text-slate-500">Periode otomatis</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4">
                                                <span class="inline-flex rounded-md bg-amber-50 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] text-amber-700 ring-1 ring-amber-100">
                                                    @switch($recurring->frequency)
                                                        @case('daily') Harian @break
                                                        @case('weekly') Mingguan @break
                                                        @case('monthly') Bulanan @break
                                                        @case('yearly') Tahunan @break
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 text-slate-600">{{ optional($recurring->account)->name ?? '-' }}</td>
                                            <td class="px-4 py-4 text-slate-600">{{ optional($recurring->start_date)->format('d M Y') ?? '-' }}</td>
                                            <td class="px-4 py-4 text-right font-bold text-rose-700">
                                                -Rp{{ number_format($recurring->amount, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="flex justify-end gap-2">
                                                    <a href="{{ route('transactions.showRecurring', $recurring) }}"
                                                        class="ui-button inline-flex h-9 w-9 items-center justify-center rounded-md bg-white text-slate-700 shadow-sm ring-1 ring-slate-200 hover:bg-slate-50 hover:ring-slate-300"
                                                        aria-label="Lihat transaksi berulang">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('transactions.editRecurring', $recurring) }}"
                                                        class="ui-button inline-flex h-9 w-9 items-center justify-center rounded-md bg-white text-amber-700 shadow-sm ring-1 ring-slate-200 hover:bg-amber-50 hover:ring-amber-100"
                                                        aria-label="Edit transaksi berulang">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('transactions.destroyRecurring', $recurring) }}" method="POST"
                                                        onsubmit="return confirm('Hapus transaksi berulang ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="ui-button inline-flex h-9 w-9 items-center justify-center rounded-md bg-white text-red-600 shadow-sm ring-1 ring-red-100 hover:bg-red-50 hover:ring-red-200"
                                                            aria-label="Hapus transaksi berulang">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>
                @endif

                <section class="rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="flex flex-col gap-3 border-b border-slate-200 px-4 py-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Daftar Transaksi</p>
                            <h2 class="mt-1 text-lg font-semibold text-slate-950">Riwayat transaksi</h2>
                        </div>

                        <span class="inline-flex w-fit rounded-md bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                            {{ $transactionCount }} transaksi tampil
                        </span>
                    </div>

                    @if ($transactionItems->isEmpty())
                        <div class="p-10 text-center">
                            <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-lg bg-slate-100 text-slate-400">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-slate-950">Belum ada transaksi</h3>
                            <p class="mt-2 text-sm text-slate-500">Mulai catat pemasukan atau pengeluaran pertama.</p>
                            <a href="{{ route('transactions.create') }}"
                                class="ui-button mt-6 inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-emerald-600 px-5 text-sm font-semibold text-white shadow-sm shadow-emerald-700/15 hover:bg-emerald-700">
                                Tambah Transaksi Pertama
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                                <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">
                                    <tr>
                                        <th scope="col" class="px-4 py-3">Tanggal</th>
                                        <th scope="col" class="px-4 py-3">Transaksi</th>
                                        <th scope="col" class="px-4 py-3">Akun</th>
                                        <th scope="col" class="px-4 py-3">Kategori</th>
                                        <th scope="col" class="px-4 py-3">Tag</th>
                                        <th scope="col" class="px-4 py-3">Tipe</th>
                                        <th scope="col" class="px-4 py-3 text-right">Nominal</th>
                                        <th scope="col" class="px-4 py-3 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @foreach ($transactions as $transaction)
                                        @php
                                            $isIncome = $transaction->type === 'income';
                                            $tags = collect($transaction->tags ?? []);
                                        @endphp

                                        <tr
                                            x-show="matches(@js($transaction->type), @js($transaction->title), @js(optional($transaction->account)->name), @js(optional($transaction->category)->name), @js(optional($transaction->transaction_date)->format('d M Y')))"
                                            x-transition:enter="transition ease-out duration-150"
                                            x-transition:enter-start="opacity-0"
                                            x-transition:enter-end="opacity-100"
                                            class="hover:bg-slate-50/70">
                                            <td class="whitespace-nowrap px-4 py-4 font-medium text-slate-600">
                                                {{ optional($transaction->transaction_date)->format('d M Y') ?? '-' }}
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="flex min-w-64 items-center gap-3">
                                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg ring-1 {{ $isIncome ? 'bg-emerald-50 text-emerald-700 ring-emerald-100' : 'bg-rose-50 text-rose-700 ring-rose-100' }}">
                                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            @if ($isIncome)
                                                                <path d="M12 19V5" />
                                                                <path d="m5 12 7-7 7 7" />
                                                            @else
                                                                <path d="M12 5v14" />
                                                                <path d="m19 12-7 7-7-7" />
                                                            @endif
                                                        </svg>
                                                    </span>
                                                    <div class="min-w-0">
                                                        <p class="font-semibold text-slate-950">{{ $transaction->title }}</p>
                                                        <p class="mt-1 text-xs text-slate-500">
                                                            {{ $isIncome ? 'Dana masuk' : 'Dana keluar' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-4 text-slate-600">
                                                {{ optional($transaction->account)->name ?? '-' }}
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-4">
                                                <span class="rounded-md bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                                    {{ optional($transaction->category)->name ?? 'Tanpa kategori' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4">
                                                @if ($tags->isNotEmpty())
                                                    <div class="flex min-w-32 flex-wrap gap-1.5">
                                                        @foreach ($tags->take(2) as $tag)
                                                            <span class="rounded-md px-2 py-1 text-[11px] font-semibold text-white"
                                                                style="background-color: {{ $tag->color ?? '#6B7280' }}">
                                                                {{ $tag->name }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-xs text-slate-400">-</span>
                                                @endif
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-4">
                                                <span class="inline-flex rounded-md px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] ring-1 {{ $isIncome ? 'bg-emerald-50 text-emerald-700 ring-emerald-100' : 'bg-rose-50 text-rose-700 ring-rose-100' }}">
                                                    {{ $isIncome ? 'Pemasukan' : 'Pengeluaran' }}
                                                </span>
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-4 text-right font-bold {{ $isIncome ? 'text-emerald-700' : 'text-rose-700' }}">
                                                {{ $isIncome ? '+Rp' : '-Rp' }}{{ number_format($transaction->amount, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="flex justify-end gap-2">
                                                    <a href="{{ route('transactions.show', $transaction) }}"
                                                        class="ui-button inline-flex h-9 w-9 items-center justify-center rounded-md bg-white text-slate-700 shadow-sm ring-1 ring-slate-200 hover:bg-slate-50 hover:ring-slate-300"
                                                        aria-label="Lihat transaksi">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('transactions.edit', $transaction) }}"
                                                        class="ui-button inline-flex h-9 w-9 items-center justify-center rounded-md bg-white text-amber-700 shadow-sm ring-1 ring-slate-200 hover:bg-amber-50 hover:ring-amber-100"
                                                        aria-label="Edit transaksi">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('transactions.destroy', $transaction) }}" method="POST"
                                                        onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="ui-button inline-flex h-9 w-9 items-center justify-center rounded-md bg-white text-red-600 shadow-sm ring-1 ring-red-100 hover:bg-red-50 hover:ring-red-200"
                                                            aria-label="Hapus transaksi">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                    <tr x-cloak x-show="!hasMatches()">
                                        <td colspan="8" class="px-4 py-10 text-center">
                                            <h3 class="text-base font-semibold text-slate-950">Tidak ada transaksi di filter ini</h3>
                                            <p class="mt-2 text-sm text-slate-500">Pilih tipe lain atau ubah kata pencarian.</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        @if ($transactions instanceof \Illuminate\Pagination\AbstractPaginator)
                            <div class="border-t border-slate-200 px-4 py-4">
                                {{ $transactions->links() }}
                            </div>
                        @endif
                    @endif
                </section>
            </div>
        </div>
    </div>
@endsection
