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
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-[#f6f7f9] text-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-10 lg:px-8">
        
        <!-- Header -->
        <div class="mb-7 flex flex-col gap-4 border-b border-slate-200 pb-6 lg:flex-row lg:items-end lg:justify-between ui-reveal">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Moderasi Konten</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Moderasi Tag</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                    Pantau dan hapus tag buatan pengguna yang melanggar pedoman konten sistem keuangan MOMA.
                </p>
            </div>
            <div class="flex items-center gap-2 bg-red-50 border border-red-200/60 rounded-full px-4 py-2 self-start lg:self-auto shadow-sm">
                <span class="inline-flex h-2.5 w-2.5 rounded-full bg-red-500 animate-pulse"></span>
                <span class="text-xs font-semibold text-red-800">Mode Moderasi Aktif</span>
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

        <!-- Tags Moderation List -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 ui-reveal">
            <h2 class="text-xl font-bold text-slate-800 mb-5">Daftar Tag Pengguna</h2>
            @if($tags->isEmpty())
                <div class="text-center py-16 bg-slate-50/50 rounded-3xl border border-dashed border-slate-200">
                    <div class="mx-auto h-16 w-16 rounded-full bg-slate-100 flex items-center justify-center mb-4 text-slate-400">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">Belum ada tag</h3>
                    <p class="mt-1 text-xs text-slate-500">Belum ada pengguna yang membuat tag di dalam database.</p>
                </div>
            @else
                <div class="overflow-hidden rounded-2xl border border-slate-100">
                    <table class="w-full text-left text-sm text-slate-700">
                        <thead class="text-xs font-semibold text-slate-400 uppercase tracking-[0.18em] bg-slate-50">
                            <tr>
                                <th class="px-6 py-4">Tag</th>
                                <th class="px-6 py-4">Dibuat Oleh</th>
                                <th class="px-6 py-4">Penggunaan Transaksi</th>
                                <th class="px-6 py-4">Tanggal Dibuat</th>
                                <th class="px-6 py-4 text-right rounded-r-2xl">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach($tags as $tag)
                                <tr class="hover:bg-slate-50/80 transition duration-150">
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold text-white shadow-sm"
                                            style="background-color: {{ $tag->color ?? '#6b7280' }}">
                                            #{{ $tag->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-semibold text-slate-800">{{ $tag->user ? $tag->user->name : 'Sistem' }}</span>
                                        <span class="block text-xs text-slate-400 font-normal mt-0.5">{{ $tag->user ? $tag->user->email : '' }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-slate-100 text-slate-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-slate-200/40">
                                            {{ $tag->transactions_count }} transaksi
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-500 font-medium">
                                        {{ $tag->created_at ? $tag->created_at->format('d M Y, H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <form action="{{ route('auditor.tags.destroy', $tag) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus tag \'#{{ $tag->name }}\' secara permanen dari seluruh sistem? Tindakan ini tidak dapat dibatalkan.');"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 hover:bg-red-100 font-semibold py-2 px-3.5 rounded-xl transition duration-150 text-xs">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
