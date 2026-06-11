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
        <div class="mx-auto max-w-2xl px-4 py-6 sm:px-6 sm:py-10 lg:px-8">
            <div class="mb-7 border-b border-slate-200 pb-6">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Investasi Keuangan</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">{{ __('Buat Savings Goal') }}</h1>
            </div>

            <div class="ui-reveal rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <form method="POST" action="{{ route('savings-goals.store') }}" class="space-y-5">
                    @csrf

                    <!-- Goal Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Nama Goal</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}"
                            placeholder="Contoh: Liburan, Mobil Baru, Dana Darurat"
                            class="h-10 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm text-slate-700 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100"
                            required
                        >
                        @error('name')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Target Amount -->
                    <div>
                        <label for="target_amount" class="block text-sm font-semibold text-slate-700 mb-2">Target Amount (Rp)</label>
                            <input 
                            type="number" 
                            id="target_amount" 
                            name="target_amount" 
                            value="{{ old('target_amount') }}"
                            placeholder="0"
                            step="1"
                            min="0"
                            class="h-10 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm text-slate-700 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100"
                            required
                        >
                        @error('target_amount')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Amount -->
                    <div>
                        <label for="current_amount" class="block text-sm font-semibold text-slate-700 mb-2">Current Amount (Rp)</label>
                            <input 
                            type="number" 
                            id="current_amount" 
                            name="current_amount" 
                            value="{{ old('current_amount', 0) }}"
                            placeholder="0"
                            step="1"
                            min="0"
                            class="h-10 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm text-slate-700 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100"
                        >
                        @error('current_amount')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Account Selection -->
                    <div>
                        <label for="account_id" class="block text-sm font-semibold text-slate-700 mb-2">Pilih Akun Dompet</label>
                        <select 
                            id="account_id" 
                            name="account_id"
                            class="h-10 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm text-slate-700 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100"
                        >
                            <option value="">-- Pilih Akun (Opsional) --</option>
                            @forelse ($accounts as $account)
                                <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                    {{ $account->name }} - Rp{{ number_format($account->balance, 0, ',', '.') }}
                                </option>
                            @empty
                                <option value="" disabled>Tidak ada akun tersedia</option>
                            @endforelse
                        </select>
                        @error('account_id')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-slate-500 mt-1">Akun yang dipilih akan digunakan untuk tracking savings goal ini</p>
                    </div>

                    <!-- Deadline -->
                    <div>
                        <label for="deadline" class="block text-sm font-semibold text-slate-700 mb-2">Target Deadline</label>
                        <input 
                            type="date" 
                            id="deadline" 
                            name="deadline" 
                            value="{{ old('deadline') }}"
                            min="{{ now()->addDay()->format('Y-m-d') }}"
                            class="h-10 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm text-slate-700 outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100"
                        >
                        @error('deadline')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-slate-500 mt-1">Opsional: Tetapkan target tanggal untuk memotivasi diri</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 pt-2">
                        <a href="{{ route('savings-goals.index') }}" class="ui-button flex-1 inline-flex h-11 items-center justify-center rounded-lg border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                            Batal
                        </a>
                        <button type="submit" class="ui-button flex-1 inline-flex h-11 items-center justify-center rounded-lg bg-emerald-600 px-4 text-sm font-semibold text-white shadow-sm shadow-emerald-700/15 hover:bg-emerald-700">
                            Buat Goal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
