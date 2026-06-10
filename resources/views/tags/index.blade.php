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

    .tag-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 600;
        color: #fff;
        letter-spacing: 0.03em;
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

            @if ($tags->isEmpty())
                <div class="ui-reveal rounded-lg border border-slate-200 bg-white p-10 text-center shadow-sm">
                    <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-violet-50 text-violet-500 ring-2 ring-violet-100">
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
                <div class="ui-reveal grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach ($tags as $tag)
                        <div class="ui-card group relative flex flex-col overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                            {{-- Color banner --}}
                            <div class="h-2 w-full" style="background-color: {{ $tag->color ?? '#6B7280' }}"></div>

                            <div class="flex flex-1 flex-col gap-4 p-5">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-white text-sm font-bold shadow-sm"
                                            style="background-color: {{ $tag->color ?? '#6B7280' }}">
                                            {{ mb_strtoupper(mb_substr($tag->name, 0, 1)) }}
                                        </span>
                                        <div>
                                            <p class="font-semibold text-slate-950">{{ $tag->name }}</p>
                                            <p class="text-xs text-slate-500 mt-0.5">
                                                {{ $tag->transactions_count }} transaksi
                                            </p>
                                        </div>
                                    </div>

                                    <span class="tag-chip shrink-0" style="background-color: {{ $tag->color ?? '#6B7280' }}">
                                        #{{ $tag->name }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-2 border-t border-slate-100 pt-4">
                                    <a href="{{ route('tags.edit', $tag) }}"
                                        class="ui-button flex-1 inline-flex h-9 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 px-3 text-xs font-semibold text-slate-700 hover:bg-white hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-slate-300">
                                        <svg class="mr-1.5 h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('tags.destroy', $tag) }}" method="POST"
                                        onsubmit="return confirm('Hapus tag \'{{ $tag->name }}\' ? Tag yang dihapus tidak bisa dikembalikan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="ui-button inline-flex h-9 w-9 items-center justify-center rounded-lg border border-red-100 bg-red-50 text-red-600 hover:bg-red-100 hover:border-red-200 focus:outline-none focus:ring-2 focus:ring-red-300">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <p class="mt-6 text-center text-xs text-slate-400">
                    Total {{ $tags->count() }} tag terdaftar
                </p>
            @endif
        </div>
    </div>
@endsection
