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
                $typeLabel = $isIncome ? 'Pemasukan' : 'Pengeluaran';
                $typeSoft = $isIncome ? 'bg-emerald-50 text-emerald-700 ring-emerald-100' : 'bg-rose-50 text-rose-700 ring-rose-100';
                $amountColor = $isIncome ? 'text-emerald-300' : 'text-rose-300';
            @endphp

            <div class="mb-7 flex flex-col gap-4 border-b border-slate-200 pb-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Detail Transaksi</p>
                    <h1 class="mt-2 break-words text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">{{ $transaction->title }}</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">Informasi lengkap transaksi dan catatan pencatatan.</p>
                </div>

                <div class="flex flex-col gap-2 sm:flex-row">
                    <a href="{{ route('transactions.index') }}"
                        class="ui-button inline-flex h-11 items-center justify-center rounded-lg border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm hover:border-slate-300 hover:bg-slate-50">
                        Kembali
                    </a>
                    <a href="{{ route('transactions.edit', $transaction) }}"
                        class="ui-button inline-flex h-11 items-center justify-center rounded-lg bg-slate-950 px-4 text-sm font-semibold text-white shadow-sm shadow-slate-900/10 hover:bg-slate-800">
                        Edit
                    </a>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_360px]">
                <main class="space-y-5">
                    <section class="ui-reveal overflow-hidden rounded-lg bg-slate-950 p-6 text-white shadow-lg shadow-slate-900/10">
                        <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <span class="inline-flex rounded-md px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] ring-1 {{ $typeSoft }}">
                                    {{ $typeLabel }}
                                </span>
                                <p class="mt-4 text-4xl font-bold tracking-tight {{ $amountColor }} sm:text-5xl">
                                    {{ $isIncome ? '+' . $currencySymbol : '-' . $currencySymbol }}{{ number_format($transaction->amount, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="rounded-lg border border-white/10 bg-white/[0.06] px-4 py-3">
                                <p class="text-xs text-slate-400">Tanggal</p>
                                <p class="mt-1 text-sm font-semibold text-white">{{ $transaction->transaction_date->format('d M Y') }}</p>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Informasi Transaksi</p>
                        <div class="mt-5 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-lg bg-slate-50 p-4 ring-1 ring-slate-200">
                                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Akun</p>
                                <p class="mt-2 text-lg font-semibold text-slate-950">{{ optional($transaction->account)->name ?? '-' }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ ucfirst(optional($transaction->account)->type ?? '-') }}</p>
                            </div>

                            <div class="rounded-lg bg-slate-50 p-4 ring-1 ring-slate-200">
                                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Kategori</p>
                                <p class="mt-2 text-lg font-semibold text-slate-950">{{ optional($transaction->category)->name ?? '-' }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ ucfirst(optional($transaction->category)->type ?? '-') }}</p>
                            </div>

                            @if ($transaction->budgeting_id)
                                <div class="rounded-lg bg-emerald-50 p-4 ring-1 ring-emerald-100">
                                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-emerald-700">Budget</p>
                                    <p class="mt-2 text-lg font-semibold text-emerald-800">{{ optional($transaction->budgeting)->name }}</p>
                                    <p class="mt-1 text-xs text-emerald-600">Limit: {{ $currencySymbol }}{{ number_format(optional($transaction->budgeting)->limit_amount, 0, ',', '.') }}</p>
                                </div>
                            @endif

                            <div class="rounded-lg bg-slate-50 p-4 ring-1 ring-slate-200">
                                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Hari</p>
                                <p class="mt-2 text-lg font-semibold text-slate-950">{{ $transaction->transaction_date->format('l') }}</p>
                            </div>

                            <div class="rounded-lg bg-slate-50 p-4 ring-1 ring-slate-200">
                                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Status</p>
                                <p class="mt-2 text-lg font-semibold text-slate-950">Tercatat</p>
                            </div>
                        </div>
                    </section>

                    @if ($transaction->description)
                        <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Catatan</p>
                            <p class="mt-3 text-sm leading-6 text-slate-700">{{ $transaction->description }}</p>
                        </section>
                    @endif

                    @if ($transaction->tags->count() > 0)
                        <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Tag</p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach ($transaction->tags as $tag)
                                    <span class="rounded-md px-2.5 py-1 text-xs font-semibold text-white"
                                        style="background-color: {{ $tag->color ?? '#6B7280' }}">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        </section>
                    @endif
                </main>

                <aside class="space-y-5">
                    <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Aksi</p>
                        <div class="mt-4 space-y-2">
                            <a href="{{ route('transactions.edit', $transaction) }}"
                                class="ui-button inline-flex h-11 w-full items-center justify-center rounded-lg bg-slate-950 px-4 text-sm font-semibold text-white shadow-sm shadow-slate-900/10 hover:bg-slate-800">
                                Edit Transaksi
                            </a>
                            <form action="{{ route('transactions.destroy', $transaction) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="ui-button inline-flex h-11 w-full items-center justify-center rounded-lg bg-white px-4 text-sm font-semibold text-red-600 shadow-sm ring-1 ring-red-100 hover:bg-red-50">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </section>

                    <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Audit</p>
                        <div class="mt-4 space-y-3 text-sm">
                            <div>
                                <p class="font-semibold text-slate-500">Dibuat</p>
                                <p class="mt-1 text-slate-950">{{ $transaction->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-500">Terakhir diubah</p>
                                <p class="mt-1 text-slate-950">{{ $transaction->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </section>
                </aside>
            </div>
        </div>
    </div>
@endsection
