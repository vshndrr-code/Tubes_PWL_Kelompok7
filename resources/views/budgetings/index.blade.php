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
                $budgetItems =
                    $budgetings instanceof \Illuminate\Pagination\AbstractPaginator
                        ? $budgetings->getCollection()
                        : collect($budgetings);

                $monthLabels = [
                    1 => 'Januari',
                    2 => 'Februari',
                    3 => 'Maret',
                    4 => 'April',
                    5 => 'Mei',
                    6 => 'Juni',
                    7 => 'Juli',
                    8 => 'Agustus',
                    9 => 'September',
                    10 => 'Oktober',
                    11 => 'November',
                    12 => 'Desember',
                ];

                $activeMonth = (int) request('month', now()->month);
                $activeYear = (int) request('year', now()->year);
                $periodBudgets = $budgetItems->filter(fn($budget) => (int) $budget->month === $activeMonth && (int) $budget->year === $activeYear);
                $displayBudgets = $periodBudgets->isNotEmpty() ? $periodBudgets : $budgetItems;
                $initialMonthFilter = $periodBudgets->isNotEmpty() ? (string) $activeMonth : 'all';
                $initialYearFilter = $periodBudgets->isNotEmpty() ? (string) $activeYear : '';
                $periodLabel = $periodBudgets->isNotEmpty() ? (($monthLabels[$activeMonth] ?? '-') . ' ' . $activeYear) : 'Semua periode';
                $totalBudget = $displayBudgets->sum('limit_amount');
                $totalUsed = $displayBudgets->sum(fn($budget) => (float) (data_get($budget, 'spent_amount') ?? data_get($budget, 'used_amount') ?? data_get($budget, 'spent') ?? 0));
                $totalRemaining = $totalBudget - $totalUsed;
                $overallProgress = $totalBudget > 0 ? min(($totalUsed / $totalBudget) * 100, 100) : 0;
                $categoryCount = $displayBudgets->pluck('category_id')->filter()->unique()->count();
            @endphp

            <div x-data="{
                search: '',
                month: @js($initialMonthFilter),
                year: @js($initialYearFilter),
                budgets: @js($budgetItems->map(fn($item) => [
                    'category' => optional($item->category)->name,
                    'month' => (string) $item->month,
                    'year' => (string) $item->year,
                ])->values()),
                matches(category = '', itemMonth = '', itemYear = '') {
                    const text = `${category ?? ''} ${itemMonth ?? ''} ${itemYear ?? ''}`.toLowerCase();
                    const matchesMonth = this.month === 'all' || this.month === itemMonth;
                    const matchesYear = this.year === '' || this.year === itemYear;
                    const matchesSearch = this.search === '' || text.includes(this.search.toLowerCase());
                    return matchesMonth && matchesYear && matchesSearch;
                },
                hasMatches() {
                    return this.budgets.some(item => this.matches(item.category, item.month, item.year));
                }
            }">
                <div class="mb-7 flex flex-col gap-4 border-b border-slate-200 pb-6 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Pusat Budget</p>
                        <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Budgeting</h1>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                            Atur limit kategori, pantau sisa alokasi, dan jaga rencana bulanan tetap terkendali.
                        </p>
                    </div>

                    <a href="{{ route('budgetings.create') }}"
                        class="ui-button inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 text-sm font-semibold text-white shadow-sm shadow-emerald-700/15 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-[#f6f7f9]">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Budget
                    </a>
                </div>

                @if (session('success'))
                    <div class="mb-6 rounded-lg border border-emerald-200 bg-white px-4 py-3 text-sm font-medium text-emerald-800 shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mb-6 grid gap-4 lg:grid-cols-[1.35fr_0.65fr]">
                    <section class="ui-reveal rounded-lg bg-slate-950 p-6 text-white shadow-lg shadow-slate-900/10">
                        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Budget Periode Ini</p>
                                <p class="mt-3 text-3xl font-bold tracking-tight sm:text-5xl">
                                    Rp{{ number_format($totalBudget, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="rounded-lg border border-white/10 bg-white/[0.06] px-4 py-3">
                                <p class="text-xs text-slate-400">Periode</p>
                                <p class="mt-1 text-sm font-semibold text-white">
                                    {{ $periodLabel }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="flex items-center justify-between gap-4 text-sm">
                                <span class="font-medium text-slate-300">Terpakai Rp{{ number_format($totalUsed, 0, ',', '.') }}</span>
                                <span class="font-semibold text-white">{{ number_format($overallProgress, 0) }}%</span>
                            </div>
                            <div class="mt-3 h-3 overflow-hidden rounded-full bg-white/10">
                                <div class="h-full rounded-full bg-emerald-400" style="width: {{ $overallProgress }}%"></div>
                            </div>
                        </div>
                    </section>

                    <div class="grid gap-4 sm:grid-cols-3 lg:grid-cols-1">
                        <div class="ui-card rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Sisa Alokasi</p>
                            <p class="mt-3 text-2xl font-bold {{ $totalRemaining < 0 ? 'text-rose-700' : 'text-emerald-700' }}">
                                {{ $totalRemaining < 0 ? '-Rp' : 'Rp' }}{{ number_format(abs($totalRemaining), 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="ui-card rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Kategori</p>
                            <p class="mt-3 text-3xl font-bold text-slate-950">{{ $categoryCount }}</p>
                        </div>

                        <div class="ui-card rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Budget</p>
                            <p class="mt-3 text-3xl font-bold text-slate-950">{{ $displayBudgets->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid gap-6">
                    <main class="space-y-5">
                        <section class="rounded-lg border border-slate-200 bg-white p-3 shadow-sm">
                            <div class="flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between">
                                <div class="relative w-full xl:max-w-md">
                                    <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                                    </svg>
                                    <input
                                        x-model="search"
                                        type="search"
                                        placeholder="Cari kategori budget..."
                                        class="h-10 w-full rounded-lg border border-slate-200 bg-slate-50 px-10 text-sm text-slate-700 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100"
                                    />
                                </div>

                                <div class="flex flex-col gap-2 sm:flex-row">
                                    <select x-model="month"
                                        class="h-10 rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm font-medium text-slate-700 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100">
                                        <option value="all">Semua bulan</option>
                                        @foreach ($monthLabels as $monthNumber => $monthName)
                                            <option value="{{ $monthNumber }}">{{ $monthName }}</option>
                                        @endforeach
                                    </select>

                                    <input x-model="year" type="number"
                                        class="h-10 rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm font-medium text-slate-700 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100"
                                        placeholder="Tahun">
                                </div>
                            </div>
                        </section>

                        <section>
                            <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Daftar Budget</p>
                                    <h2 class="mt-1 text-lg font-semibold text-slate-950">Kategori budget</h2>
                                </div>

                                <span class="inline-flex w-fit rounded-md bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                    {{ $budgetItems->count() }} budget tersimpan
                                </span>
                            </div>

                            @if ($budgetItems->isEmpty())
                                <div class="rounded-lg border border-dashed border-slate-300 bg-white p-10 text-center shadow-sm">
                                    <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-lg bg-slate-100 text-slate-400">
                                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-semibold text-slate-950">Belum ada budget</h3>
                                    <p class="mt-2 text-sm text-slate-500">Tambahkan budget pertama untuk mulai menyusun rencana bulanan.</p>
                                </div>
                            @else
                                <div class="grid gap-4 md:grid-cols-2">
                                    @foreach ($budgetings as $budget)
                                        @php
                                            $limitAmount = (float) $budget->limit_amount;
                                            $usedAmount = (float) (data_get($budget, 'spent_amount') ?? data_get($budget, 'used_amount') ?? data_get($budget, 'spent') ?? 0);
                                            $remainingAmount = $limitAmount - $usedAmount;
                                            $progress = $limitAmount > 0 ? min(($usedAmount / $limitAmount) * 100, 100) : 0;
                                            $isOver = $remainingAmount < 0;
                                            $isWarning = ! $isOver && $progress >= 80;
                                            $barColor = $isOver ? 'bg-rose-500' : ($isWarning ? 'bg-amber-500' : 'bg-emerald-500');
                                            $badgeClass = $isOver
                                                ? 'bg-rose-50 text-rose-700 ring-rose-100'
                                                : ($isWarning ? 'bg-amber-50 text-amber-700 ring-amber-100' : 'bg-emerald-50 text-emerald-700 ring-emerald-100');
                                            $statusText = $isOver ? 'Lewat limit' : ($isWarning ? 'Hampir penuh' : 'Terkendali');
                                        @endphp

                                        <article
                                            x-show="matches(@js(optional($budget->category)->name), @js((string) $budget->month), @js((string) $budget->year))"
                                            x-transition:enter="transition ease-out duration-150"
                                            x-transition:enter-start="opacity-0 translate-y-1"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            class="ui-card rounded-lg border border-slate-200 bg-white p-5 shadow-sm hover:border-slate-300 hover:shadow-md">
                                            <div class="flex items-start justify-between gap-4">
                                                <div class="flex min-w-0 items-start gap-3">
                                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
                                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M4 19V5" />
                                                            <path d="M8 17V9" />
                                                            <path d="M12 15V7" />
                                                            <path d="M16 19v-6" />
                                                            <path d="M20 17V4" />
                                                        </svg>
                                                    </div>

                                                    <div class="min-w-0">
                                                        <h3 class="break-words text-lg font-semibold text-slate-950">
                                                            {{ $budget->category->name ?? '-' }}
                                                        </h3>
                                                        <p class="mt-1 text-sm text-slate-500">
                                                            {{ $monthLabels[(int) $budget->month] ?? str_pad($budget->month, 2, '0', STR_PAD_LEFT) }} {{ $budget->year }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <span class="inline-flex shrink-0 rounded-md px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] ring-1 {{ $badgeClass }}">
                                                    {{ $statusText }}
                                                </span>
                                            </div>

                                            <div class="mt-5 grid grid-cols-2 gap-3">
                                                <div class="rounded-lg bg-slate-50 p-3 ring-1 ring-slate-200">
                                                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Limit</p>
                                                    <p class="mt-2 text-lg font-bold text-slate-950">
                                                        Rp{{ number_format($limitAmount, 0, ',', '.') }}
                                                    </p>
                                                </div>

                                                <div class="rounded-lg bg-slate-50 p-3 ring-1 ring-slate-200">
                                                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Sisa</p>
                                                    <p class="mt-2 text-lg font-bold {{ $remainingAmount < 0 ? 'text-rose-700' : 'text-emerald-700' }}">
                                                        {{ $remainingAmount < 0 ? '-Rp' : 'Rp' }}{{ number_format(abs($remainingAmount), 0, ',', '.') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="mt-5">
                                                <div class="flex items-center justify-between gap-4 text-sm">
                                                    <span class="font-medium text-slate-500">Terpakai Rp{{ number_format($usedAmount, 0, ',', '.') }}</span>
                                                    <span class="font-semibold text-slate-700">{{ number_format($progress, 0) }}%</span>
                                                </div>
                                                <div class="mt-2 h-2.5 overflow-hidden rounded-full bg-slate-100">
                                                    <div class="h-full rounded-full {{ $barColor }}" style="width: {{ $progress }}%"></div>
                                                </div>
                                            </div>

                                            <div class="mt-5 flex justify-end gap-2 border-t border-slate-100 pt-4">
                                                <a href="{{ route('budgetings.edit', $budget->id) }}"
                                                    class="ui-button inline-flex h-9 items-center justify-center gap-2 rounded-md bg-white px-3 text-sm font-semibold text-amber-700 shadow-sm ring-1 ring-slate-200 hover:bg-amber-50 hover:ring-amber-100">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit
                                                </a>

                                                <form action="{{ route('budgetings.destroy', $budget->id) }}" method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus budget ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="ui-button inline-flex h-9 items-center justify-center gap-2 rounded-md bg-white px-3 text-sm font-semibold text-red-600 shadow-sm ring-1 ring-red-100 hover:bg-red-50 hover:ring-red-200">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>

                                <div x-cloak x-show="!hasMatches()"
                                    class="mt-4 rounded-lg border border-dashed border-slate-300 bg-white p-10 text-center shadow-sm">
                                    <h3 class="text-lg font-semibold text-slate-950">Tidak ada budget di filter ini</h3>
                                    <p class="mt-2 text-sm text-slate-500">Pilih periode lain atau ubah kata pencarian.</p>
                                </div>

                                @if ($budgetings instanceof \Illuminate\Pagination\AbstractPaginator)
                                    <div class="mt-8">
                                        {{ $budgetings->links() }}
                                    </div>
                                @endif
                            @endif
                        </section>
                    </main>
                </div>
            </div>
        </div>
    </div>
@endsection
