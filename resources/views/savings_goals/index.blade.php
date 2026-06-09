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

                <a href="{{ route('savings-goals.create') }}" class="ui-button inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 text-sm font-semibold text-white shadow-sm shadow-emerald-700/15 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-[#f6f7f9]">
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

            @if ($savingsGoals->isEmpty())
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
                <div class="mb-5 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($savingsGoals as $goal)
                        @php
                            $progressPercent = ($goal->current_amount / $goal->target_amount) * 100;
                            $isCompleted = $goal->status === 'completed';
                            $isCancelled = $goal->status === 'cancelled';
                            $barColor = $isCompleted ? 'bg-emerald-500' : ($isCancelled ? 'bg-rose-500' : 'bg-sky-500');
                            $statusColor = $isCompleted ? 'bg-emerald-50 text-emerald-700 ring-emerald-100' : ($isCancelled ? 'bg-rose-50 text-rose-700 ring-rose-100' : 'bg-sky-50 text-sky-700 ring-sky-100');
                        @endphp
                        <article class="ui-card rounded-lg border border-slate-200 bg-white p-5 shadow-sm hover:border-slate-300 hover:shadow-md">
                            <div class="flex items-start justify-between gap-3 mb-4">
                                <div class="min-w-0">
                                    <h3 class="truncate text-lg font-semibold text-slate-950">{{ $goal->name }}</h3>
                                    @if ($goal->account)
                                        <p class="mt-1 text-xs text-slate-500">Akun: {{ $goal->account->name }}</p>
                                    @endif
                                    @if ($goal->deadline)
                                        <p class="mt-1 text-xs text-slate-500">Target: {{ $goal->deadline->format('d M Y') }}</p>
                                    @endif
                                </div>
                                <span class="inline-flex rounded-md px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] ring-1 {{ $statusColor }}">
                                    {{ ucfirst($goal->status) }}
                                </span>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-4">
                                <div class="flex justify-between items-center gap-4 text-sm mb-2">
                                    <span class="font-medium text-slate-600">Progress</span>
                                    <span class="font-semibold text-slate-950">{{ number_format(min(100, $progressPercent), 1) }}%</span>
                                </div>
                                <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full transition-all {{ $barColor }}" style="width: {{ min(100, $progressPercent) }}%"></div>
                                </div>
                            </div>

                            <!-- Amount Info -->
                            <div class="mb-4 grid gap-3 grid-cols-3 rounded-lg bg-slate-50 p-3 text-sm ring-1 ring-slate-200">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.1em] text-slate-500">Saat ini</p>
                                    <p class="mt-1 font-semibold text-slate-900">Rp{{ number_format($goal->current_amount, 0, ',', '.') }}</p>
                                </div>
                                <div class="border-l border-r border-slate-200">
                                    <p class="text-xs font-semibold uppercase tracking-[0.1em] text-slate-500">Target</p>
                                    <p class="mt-1 font-semibold text-slate-900">Rp{{ number_format($goal->target_amount, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.1em] text-slate-500">Sisa</p>
                                    <p class="mt-1 font-semibold {{ max(0, $goal->target_amount - $goal->current_amount) <= 0 ? 'text-emerald-700' : 'text-slate-900' }}">Rp{{ number_format(max(0, $goal->target_amount - $goal->current_amount), 0, ',', '.') }}</p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2">
                                <a href="{{ route('savings-goals.show', $goal->id) }}" class="flex-1 ui-button inline-flex h-10 items-center justify-center rounded-lg border border-slate-200 bg-white px-3 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                                    Detail
                                </a>
                                <a href="{{ route('savings-goals.edit', $goal->id) }}" class="flex-1 ui-button inline-flex h-10 items-center justify-center rounded-lg bg-sky-600 px-3 text-xs font-semibold text-white shadow-sm shadow-sky-700/15 hover:bg-sky-700">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('savings-goals.destroy', $goal->id) }}" class="flex-1" onsubmit="return confirm('Yakin ingin menghapus goal ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ui-button inline-flex h-10 w-full items-center justify-center rounded-lg bg-white px-3 text-xs font-semibold text-red-600 shadow-sm ring-1 ring-red-100 hover:bg-red-50">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
