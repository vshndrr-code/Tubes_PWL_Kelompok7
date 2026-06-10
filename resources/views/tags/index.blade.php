@extends('layouts.app')

@push('head')
<style>
    @keyframes soft-enter {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @media (prefers-reduced-motion: no-preference) {
        .ui-reveal { animation: soft-enter .42s ease-out both; }
        .ui-card, .ui-button {
            transition: transform .18s ease, box-shadow .18s ease,
                        border-color .18s ease, background-color .18s ease, color .18s ease;
        }
        .ui-card:hover, .ui-button:hover { transform: translateY(-2px); }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-[#f6f7f9] text-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-10 lg:px-8">

        {{-- Header --}}
        <div class="mb-7 flex flex-col gap-4 border-b border-slate-200 pb-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Manajemen Label</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Tags</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                    Buat dan kelola label untuk mengorganisir transaksimu dengan lebih mudah.
                </p>
            </div>
            <a href="{{ route('tags.create') }}"
                class="ui-button inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-violet-600 px-4 text-sm font-semibold text-white shadow-sm shadow-violet-700/15 hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 focus:ring-offset-[#f6f7f9]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Tag Baru
            </a>
        </div>

        {{-- Flash Message --}}
        @if (session('success'))
            <div class="mb-6 rounded-lg border border-emerald-200 bg-white px-4 py-3 text-sm font-medium text-emerald-800 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @php
            $total = $tags->count();
            $totalUsed = $tags->where('transactions_count', '>', 0)->count();
        @endphp

        {{-- Stats Banner --}}
        <div class="mb-6 grid gap-4 lg:grid-cols-[1.35fr_0.65fr]">
            <section class="ui-reveal rounded-lg bg-slate-950 p-6 text-white shadow-lg shadow-slate-900/10">
                <div class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Total Tag</p>
                        <p class="mt-3 text-5xl font-bold tracking-tight">{{ $total }}</p>
                        <p class="mt-2 text-sm text-slate-400">label terdaftar di akun kamu</p>
                    </div>
                    <div class="rounded-lg border border-white/10 bg-white/[0.06] px-4 py-3">
                        <p class="text-xs text-slate-400">Tag Dipakai</p>
                        <p class="mt-1 text-sm font-semibold text-white">{{ $totalUsed }} dari {{ $total }}</p>
                    </div>
                </div>
            </section>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
                <div class="ui-card rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Tag Aktif</p>
                    <p class="mt-3 text-3xl font-bold text-slate-950">{{ $totalUsed }}</p>
                </div>
                <div class="ui-card rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Belum Dipakai</p>
                    <p class="mt-3 text-3xl font-bold text-slate-950">{{ $total - $totalUsed }}</p>
                </div>
            </div>
        </div>

        @if ($tags->isEmpty())
            <div class="ui-reveal rounded-lg border border-dashed border-slate-300 bg-white p-10 text-center shadow-sm">
                <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-lg bg-slate-100 text-slate-400">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-slate-950">Belum ada tag</h3>
                <p class="mt-2 text-sm text-slate-500">Buat tag pertamamu untuk mengorganisir transaksi dengan lebih rapi.</p>
                <a href="{{ route('tags.create') }}"
                    class="ui-button mt-6 inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-violet-600 px-5 text-sm font-semibold text-white shadow-sm hover:bg-violet-700">
                    Buat Tag Pertama
                </a>
            </div>
        @else
            {{-- Section header --}}
            <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Daftar Label</p>
                    <h2 class="mt-1 text-lg font-semibold text-slate-950">Semua Tag Anda</h2>
                </div>
                <span class="inline-flex w-fit rounded-md bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                    {{ $total }} tag terdaftar
                </span>
            </div>

            <div class="ui-reveal grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($tags as $tag)
                    <article class="ui-card group relative flex flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm hover:border-slate-300 hover:shadow-md">
                        {{-- Color accent bar --}}
                        <div class="h-1.5 w-full" style="background-color: {{ $tag->color ?? '#6B7280' }}"></div>

                        <div class="flex flex-1 flex-col gap-4 p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-white text-sm font-bold shadow-sm"
                                        style="background-color: {{ $tag->color ?? '#6B7280' }}">
                                        {{ mb_strtoupper(mb_substr($tag->name, 0, 1)) }}
                                    </span>
                                    <div>
                                        <p class="font-semibold text-slate-950">{{ $tag->name }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $tag->transactions_count }} transaksi</p>
                                    </div>
                                </div>

                                <span class="inline-flex shrink-0 rounded-md px-2 py-0.5 text-[11px] font-semibold text-white"
                                    style="background-color: {{ $tag->color ?? '#6B7280' }}">
                                    #{{ $tag->name }}
                                </span>
                            </div>

                            {{-- Stats row --}}
                            <div class="rounded-lg bg-slate-50 px-3 py-2 ring-1 ring-slate-200">
                                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Warna</p>
                                <div class="mt-1.5 flex items-center gap-2">
                                    <span class="h-4 w-4 rounded-full shadow-sm ring-1 ring-slate-200"
                                        style="background-color: {{ $tag->color ?? '#6B7280' }}"></span>
                                    <span class="font-mono text-xs text-slate-600">{{ $tag->color ?? '#6B7280' }}</span>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-2 border-t border-slate-100 pt-2">
                                <a href="{{ route('tags.edit', $tag) }}"
                                    class="ui-button flex-1 inline-flex h-9 items-center justify-center gap-1.5 rounded-lg bg-white px-3 text-xs font-semibold text-amber-700 shadow-sm ring-1 ring-slate-200 hover:bg-amber-50 hover:ring-amber-100">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('tags.destroy', $tag) }}" method="POST"
                                    onsubmit="return confirm('Hapus tag \'{{ $tag->name }}\' ? Tindakan ini tidak bisa dibatalkan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="ui-button inline-flex h-9 w-9 items-center justify-center rounded-lg bg-white text-red-600 shadow-sm ring-1 ring-red-100 hover:bg-red-50 hover:ring-red-200 focus:outline-none focus:ring-2 focus:ring-red-300">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
