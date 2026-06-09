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
                $goalItems = collect($savingsGoals);

                $totalTarget = $goalItems->sum('target_amount');
                $totalSaved = $goalItems->sum('current_amount');
                $totalRemaining = max(0, $totalTarget - $totalSaved);
                $overallProgress = $totalTarget > 0 ? min(($totalSaved / $totalTarget) * 100, 100) : 0;

                $activeCount = $goalItems->where('status', 'active')->count();
                $completedCount = $goalItems->where('status', 'completed')->count();

                // Get unique accounts for filtering
                $accountsForFilter = $goalItems->pluck('account')->filter()->unique('id');
            @endphp

            <div x-data="{
                search: '',
                status: 'all',
                accountId: 'all',
                goals: @js($goalItems->map(fn($item) => [
                    'name' => $item->name,
                    'status' => $item->status,
                    'accountId' => (string) $item->account_id,
                    'accountName' => optional($item->account)->name ?? '',
                ])->values()),
                matches(name = '', itemStatus = '', itemAccountId = '', itemAccountName = '') {
                    const text = `${name ?? ''} ${itemAccountName ?? ''}`.toLowerCase();
                    const matchesStatus = this.status === 'all' || this.status === itemStatus;
                    const matchesAccount = this.accountId === 'all' || this.accountId === itemAccountId;
                    const matchesSearch = this.search === '' || text.includes(this.search.toLowerCase());
                    return matchesStatus && matchesAccount && matchesSearch;
                },
                hasMatches() {
                    return this.goals.some(item => this.matches(item.name, item.status, item.accountId, item.accountName));
                }
            }">
                <div class="mb-7 flex flex-col gap-4 border-b border-slate-200 pb-6 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Pusat Investasi</p>
                        <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">
                            {{ __('Savings Goals') }}
                        </h1>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                            Tetapkan tujuan keuangan, pantau kemajuan, dan raih impian Anda dengan terencana.
                        </p>
                    </div>

                    <a href="{{ route('savings-goals.create') }}"
                        class="ui-button inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 text-sm font-semibold text-white shadow-sm shadow-emerald-700/15 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-[#f6f7f9]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Buat Goal
                    </a>
                </div>

                @if (session('status'))
                    <div class="mb-6 rounded-lg border border-emerald-200 bg-white px-4 py-3 text-sm font-medium text-emerald-800 shadow-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="mb-6 grid gap-4 lg:grid-cols-[1.35fr_0.65fr]">
                    <section class="ui-reveal rounded-lg bg-slate-950 p-6 text-white shadow-lg shadow-slate-900/10">
                        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Total Target Goal</p>
                                <p class="mt-3 text-3xl font-bold tracking-tight sm:text-5xl">
                                    Rp{{ number_format($totalTarget, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="rounded-lg border border-white/10 bg-white/[0.06] px-4 py-3">
                                <p class="text-xs text-slate-400">Total Terkumpul</p>
                                <p class="mt-1 text-sm font-semibold text-white">
                                    Rp{{ number_format($totalSaved, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="flex items-center justify-between gap-4 text-sm">
                                <span class="font-medium text-slate-300">Kemajuan Keseluruhan</span>
                                <span class="font-semibold text-white">{{ number_format($overallProgress, 0) }}%</span>
                            </div>
                            <div class="mt-3 h-3 overflow-hidden rounded-full bg-white/10">
                                <div class="h-full rounded-full bg-emerald-400" style="width: {{ $overallProgress }}%"></div>
                            </div>
                        </div>
                    </section>

                    <div class="grid gap-4 sm:grid-cols-3 lg:grid-cols-1">
                        <div class="ui-card rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Sisa Target</p>
                            <p class="mt-3 text-2xl font-bold {{ $totalRemaining <= 0 ? 'text-emerald-700' : 'text-sky-700' }}">
                                Rp{{ number_format($totalRemaining, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="ui-card rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Goal Aktif</p>
                            <p class="mt-3 text-3xl font-bold text-slate-950">{{ $activeCount }}</p>
                        </div>

                        <div class="ui-card rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Total Goal</p>
                            <p class="mt-3 text-3xl font-bold text-slate-950">{{ $goalItems->count() }}</p>
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
                                        placeholder="Cari nama goal atau akun..."
                                        class="h-10 w-full rounded-lg border border-slate-200 bg-slate-50 px-10 text-sm text-slate-700 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100"
                                    />
                                </div>

                                <div class="flex flex-col gap-2 sm:flex-row">
                                    <select x-model="status"
                                        class="h-10 rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm font-medium text-slate-700 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100">
                                        <option value="all">Semua status</option>
                                        <option value="active">Aktif</option>
                                        <option value="completed">Selesai</option>
                                        <option value="cancelled">Dibatalkan</option>
                                    </select>

                                    <select x-model="accountId"
                                        class="h-10 rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm font-medium text-slate-700 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100">
                                        <option value="all">Semua akun</option>
                                        @foreach ($accountsForFilter as $acc)
                                            <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </section>

                        <section>
                            <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Daftar Goal</p>
                                    <h2 class="mt-1 text-lg font-semibold text-slate-950">Savings Goal Anda</h2>
                                </div>

                                <span class="inline-flex w-fit rounded-md bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                    {{ $goalItems->count() }} goal tersimpan
                                </span>
                            </div>

                            @if ($goalItems->isEmpty())
                                <div class="rounded-lg border border-dashed border-slate-300 bg-white p-10 text-center shadow-sm">
                                    <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-lg bg-slate-100 text-slate-400">
                                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-semibold text-slate-950">Belum ada savings goal</h3>
                                    <p class="mt-2 text-sm text-slate-500">Mulai wujudkan impian keuangan Anda dengan membuat goal pertama.</p>
                                    <a href="{{ route('savings-goals.create') }}" class="ui-button mt-6 inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 text-sm font-semibold text-white shadow-sm shadow-emerald-700/15 hover:bg-emerald-700">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Buat Goal Pertama
                                    </a>
                                </div>
                            @else
                                <div class="grid gap-4 md:grid-cols-2">
                                    @foreach ($savingsGoals as $goal)
                                        @php
                                            $progressPercent = $goal->target_amount > 0 ? ($goal->current_amount / $goal->target_amount) * 100 : 0;
                                            $isCompleted = $goal->status === 'completed';
                                            $isCancelled = $goal->status === 'cancelled';
                                            $barColor = $isCompleted ? 'bg-emerald-500' : ($isCancelled ? 'bg-rose-500' : 'bg-sky-500');
                                            $statusColor = $isCompleted ? 'bg-emerald-50 text-emerald-700 ring-emerald-100' : ($isCancelled ? 'bg-rose-50 text-rose-700 ring-rose-100' : 'bg-sky-50 text-sky-700 ring-sky-100');
                                            $statusText = $isCompleted ? 'Selesai' : ($isCancelled ? 'Batal' : 'Aktif');
                                        @endphp

                                        <article
                                            x-show="matches(@js($goal->name), @js($goal->status), @js((string) $goal->account_id), @js(optional($goal->account)->name ?? ''))"
                                            x-transition:enter="transition ease-out duration-150"
                                            x-transition:enter-start="opacity-0 translate-y-1"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            class="ui-card rounded-lg border border-slate-200 bg-white p-5 shadow-sm hover:border-slate-300 hover:shadow-md">

                                            <div class="flex items-start justify-between gap-4">
                                                <div class="flex min-w-0 items-start gap-3">
                                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-sky-50 text-sky-700 ring-1 ring-sky-100">
                                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                            <circle cx="12" cy="12" r="10" />
                                                            <circle cx="12" cy="12" r="6" />
                                                            <circle cx="12" cy="12" r="2" />
                                                        </svg>
                                                    </div>

                                                    <div class="min-w-0">
                                                        <h3 class="break-words text-lg font-semibold text-slate-950">
                                                            {{ $goal->name }}
                                                        </h3>
                                                        <div class="mt-1 flex flex-col gap-0.5 text-xs text-slate-500">
                                                            @if ($goal->account)
                                                                <p>Akun: {{ $goal->account->name }}</p>
                                                            @endif
                                                            @if ($goal->deadline)
                                                                <p>Target: {{ $goal->deadline->format('d M Y') }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <span class="inline-flex shrink-0 rounded-md px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] ring-1 {{ $statusColor }}">
                                                    {{ $statusText }}
                                                </span>
                                            </div>

                                            <div class="mt-5 grid grid-cols-3 gap-3">
                                                <div class="rounded-lg bg-slate-50 p-3 ring-1 ring-slate-200">
                                                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Saat Ini</p>
                                                    <p class="mt-2 text-sm font-bold text-slate-950">
                                                        Rp{{ number_format($goal->current_amount, 0, ',', '.') }}
                                                    </p>
                                                </div>

                                                <div class="rounded-lg bg-slate-50 p-3 ring-1 ring-slate-200">
                                                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Target</p>
                                                    <p class="mt-2 text-sm font-bold text-slate-950">
                                                        Rp{{ number_format($goal->target_amount, 0, ',', '.') }}
                                                    </p>
                                                </div>

                                                <div class="rounded-lg bg-slate-50 p-3 ring-1 ring-slate-200">
                                                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Sisa</p>
                                                    <p class="mt-2 text-sm font-bold {{ $goal->target_amount - $goal->current_amount <= 0 ? 'text-emerald-700' : 'text-slate-950' }}">
                                                        Rp{{ number_format(max(0, $goal->target_amount - $goal->current_amount), 0, ',', '.') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="mt-5">
                                                <div class="flex items-center justify-between gap-4 text-sm">
                                                    <span class="font-medium text-slate-500">Progress</span>
                                                    <span class="font-semibold text-slate-700">{{ number_format(min(100, $progressPercent), 1) }}%</span>
                                                </div>
                                                <div class="mt-2 h-2.5 overflow-hidden rounded-full bg-slate-100">
                                                    <div class="h-full rounded-full transition-all {{ $barColor }}" style="width: {{ min(100, $progressPercent) }}%"></div>
                                                </div>
                                            </div>

                                            <div class="mt-5 flex justify-end gap-2 border-t border-slate-100 pt-4">
                                                <a href="{{ route('savings-goals.show', $goal->id) }}"
                                                    class="ui-button inline-flex h-9 items-center justify-center gap-2 rounded-md bg-white px-3 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-slate-200 hover:bg-slate-50">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Detail
                                                </a>

                                                <a href="{{ route('savings-goals.edit', $goal->id) }}"
                                                    class="ui-button inline-flex h-9 items-center justify-center gap-2 rounded-md bg-white px-3 text-sm font-semibold text-amber-700 shadow-sm ring-1 ring-slate-200 hover:bg-amber-50 hover:ring-amber-100">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit
                                                </a>

                                                <form action="{{ route('savings-goals.destroy', $goal->id) }}" method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus goal ini?');">
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
                                    <h3 class="text-lg font-semibold text-slate-950">Tidak ada savings goal di filter ini</h3>
                                    <p class="mt-2 text-sm text-slate-500">Pilih status/akun lain atau ubah kata pencarian.</p>
                                </div>
                            @endif
                        </section>
                    </main>
                </div>
            </div>
        </div>
    </div>
@endsection
