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
            <div class="mb-7 flex flex-col gap-4 border-b border-slate-200 pb-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Catat Aktivitas</p>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Tambah Transaksi</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                        Masukkan akun, kategori, nominal, tanggal, dan tag supaya riwayat keuangan tetap rapi.
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
                <form id="transaction-form" action="{{ route('transactions.store') }}" method="POST"
                    class="ui-reveal rounded-lg border border-slate-200 bg-white shadow-sm">
                    @csrf

                    <div class="border-b border-slate-200 px-5 py-4 sm:px-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Form Transaksi</p>
                        <h2 class="mt-1 text-lg font-semibold text-slate-950">Detail transaksi baru</h2>
                    </div>

                    <div class="space-y-6 p-5 sm:p-6">
                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label for="account_id" class="text-sm font-semibold text-slate-700">Akun/Dompet</label>
                                <select name="account_id" id="account_id"
                                    class="mt-2 h-11 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100 @error('account_id') border-red-400 ring-red-100 @enderror"
                                    required>
                                    <option value="">Pilih Akun</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}" @selected(old('account_id') == $account->id)>
                                            {{ $account->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('account_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="transaction_date" class="text-sm font-semibold text-slate-700">Tanggal</label>
                                <input type="date" name="transaction_date" id="transaction_date"
                                    value="{{ old('transaction_date', now()->format('Y-m-d')) }}"
                                    class="mt-2 h-11 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100 @error('transaction_date') border-red-400 ring-red-100 @enderror"
                                    required>
                                @error('transaction_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-slate-700">Kategori</label>
                            <div class="mt-2 rounded-lg border border-slate-200 bg-slate-50 p-3">
                                <x-category-selector :categories="$categories" :selected-category-id="old('category_id')" />
                            </div>
                            @error('category_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="budget-wrapper" style="display:none;">
                            <label for="budgeting_id" class="text-sm font-semibold text-slate-700">Hubungkan ke Budget (Opsional)</label>
                            <select name="budgeting_id" id="budgeting_id"
                                class="mt-2 h-11 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100 @error('budgeting_id') border-red-400 ring-red-100 @enderror">
                                <option value="">Tanpa Budget</option>
                                @foreach ($budgets as $budget)
                                    <option value="{{ $budget->id }}" @selected(old('budgeting_id') == $budget->id)>
                                        {{ $budget->name }} (Limit: Rp{{ number_format($budget->limit_amount, 0, ',', '.') }}, Periode: {{ ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'][$budget->month] ?? '-' }} {{ $budget->year }})
                                    </option>
                                @endforeach
                            </select>
                            @error('budgeting_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="savings-goal-wrapper" style="display:none;">
                            <label for="savings_goal_id" class="text-sm font-semibold text-slate-700">Hubungkan ke Saving Goals (Opsional)</label>
                            <select name="savings_goal_id" id="savings_goal_id"
                                class="mt-2 h-11 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100 @error('savings_goal_id') border-red-400 ring-red-100 @enderror">
                                <option value="">Tanpa Saving Goals</option>
                                @foreach ($savingsGoals as $goal)
                                    <option value="{{ $goal->id }}" @selected(old('savings_goal_id') == $goal->id)>
                                        {{ $goal->name }} (Target: Rp{{ number_format($goal->target_amount, 0, ',', '.') }}{{ $goal->deadline ? ', Deadline: ' . $goal->deadline->format('d M Y') : '' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('savings_goal_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>


                        <input type="hidden" name="type" id="type" value="{{ old('type', '') }}">

                        <div>
                            <label for="title" class="text-sm font-semibold text-slate-700">Judul Transaksi</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}"
                                class="mt-2 h-11 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100 @error('title') border-red-400 ring-red-100 @enderror"
                                required placeholder="Contoh: Makan siang, bensin, gaji">
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="amount" class="text-sm font-semibold text-slate-700">Jumlah</label>
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
                            <label for="description" class="text-sm font-semibold text-slate-700">Catatan</label>
                            <textarea name="description" id="description" rows="4"
                                class="mt-2 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-slate-400 focus:bg-white focus:ring-2 focus:ring-slate-100 @error('description') border-red-400 ring-red-100 @enderror"
                                placeholder="Tambahkan catatan atau detail transaksi">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-semibold text-slate-700">Tag <span class="text-xs font-normal text-slate-400">(Opsional)</span></label>
                                <a href="{{ route('tags.create') }}" target="_blank"
                                    class="inline-flex items-center gap-1 text-xs font-semibold text-violet-600 hover:text-violet-800 transition">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Buat tag baru
                                </a>
                            </div>
                            @if ($tags->isNotEmpty())
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach ($tags as $tag)
                                        <label class="inline-flex cursor-pointer items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold text-white ring-2 ring-transparent transition hover:ring-offset-1 has-[:checked]:ring-2"
                                            style="background-color: {{ $tag->color ?? '#6B7280' }}; --tw-ring-color: {{ $tag->color ?? '#6B7280' }}">
                                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                                class="h-3.5 w-3.5 rounded border-white/40 bg-white/20 text-white focus:ring-white/30"
                                                @checked(in_array($tag->id, old('tags', [])))>
                                            {{ $tag->name }}
                                        </label>
                                    @endforeach
                                </div>
                                <p class="mt-2 text-xs text-slate-400">Centang tag yang sesuai untuk transaksi ini. Opsional.</p>
                            @else
                                <div class="mt-3 flex items-center gap-3 rounded-lg border border-dashed border-slate-300 bg-slate-50 px-4 py-3">
                                    <svg class="h-5 w-5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    <p class="text-sm text-slate-500">
                                        Belum ada tag.
                                        <a href="{{ route('tags.create') }}" target="_blank" class="font-semibold text-violet-600 hover:underline">Buat tag pertamamu</a>
                                        untuk mengorganisir transaksi.
                                    </p>
                                </div>
                            @endif
                            @error('tags')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 border-t border-slate-200 px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
                        <a href="{{ route('transactions.index') }}"
                            class="ui-button inline-flex h-11 items-center justify-center rounded-lg border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-700 shadow-sm hover:border-slate-300 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300">
                            Batal
                        </a>
                        <button type="submit"
                            class="ui-button inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-emerald-600 px-5 text-sm font-semibold text-white shadow-sm shadow-emerald-700/15 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            Simpan Transaksi
                        </button>
                    </div>
                </form>

                <aside class="space-y-5">
                    <section class="ui-card overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
                        <div class="bg-slate-950 p-5 text-white">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Ringkasan</p>
                            <h3 class="mt-2 text-xl font-semibold">Transaksi baru</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-300">Data akan masuk ke akun dan kategori yang dipilih.</p>
                        </div>
                        <div class="grid grid-cols-2 gap-3 p-5">
                            <div class="rounded-lg bg-slate-50 p-4 ring-1 ring-slate-200">
                                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Akun</p>
                                <p class="mt-2 text-lg font-bold text-slate-950">{{ $accounts->count() }}</p>
                            </div>
                            <div class="rounded-lg bg-emerald-50 p-4 ring-1 ring-emerald-100">
                                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-emerald-700">Kategori</p>
                                <p class="mt-2 text-lg font-bold text-emerald-800">{{ $categories->count() }}</p>
                            </div>
                        </div>
                    </section>
                </aside>
            </div>
        </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const categories = @json($categories);
        const typeInput = document.getElementById('type');
        const form = document.getElementById('transaction-form');
        const budgetWrapper = document.getElementById('budget-wrapper');
        const savingsWrapper = document.getElementById('savings-goal-wrapper');
        const budgetSelect = document.getElementById('budgeting_id');
        const savingsSelect = document.getElementById('savings_goal_id');

        if (!form || !typeInput) return;

        function getSelectedCategoryType() {
            const categoryInputs = form.querySelectorAll('input[name="category_id"]');
            for (let input of categoryInputs) {
                if (input.value) {
                    const cat = categories.find(c => c.id == parseInt(input.value));
                    return cat ? cat.type : null;
                }
            }
            return null;
        }

        function updateVisibility(type) {
            if (type === 'expense') {
                budgetWrapper.style.display = '';
                savingsWrapper.style.display = 'none';
                savingsSelect.value = '';
            } else if (type === 'income') {
                budgetWrapper.style.display = 'none';
                savingsWrapper.style.display = '';
                budgetSelect.value = '';
            } else {
                budgetWrapper.style.display = 'none';
                savingsWrapper.style.display = 'none';
                budgetSelect.value = '';
                savingsSelect.value = '';
            }
        }

        // Listen for category selection changes via MutationObserver on hidden input
        const categoryHiddenInput = form.querySelector('input[name="category_id"]');
        if (categoryHiddenInput) {
            const observer = new MutationObserver(() => {
                const type = getSelectedCategoryType();
                updateVisibility(type);
            });
            observer.observe(categoryHiddenInput, { attributes: true, attributeFilter: ['value'] });
        }

        // Also poll for changes (since Alpine.js updates the value directly)
        let lastCategoryId = null;
        setInterval(() => {
            const type = getSelectedCategoryType();
            const inputs = form.querySelectorAll('input[name="category_id"]');
            let currentId = null;
            for (let input of inputs) { if (input.value) { currentId = input.value; break; } }
            if (currentId !== lastCategoryId) {
                lastCategoryId = currentId;
                updateVisibility(type);
            }
        }, 200);

        // On submit: set type and clear hidden fields
        form.addEventListener('submit', function() {
            const type = getSelectedCategoryType();
            if (type) typeInput.value = type;

            // Clear irrelevant fields before submit
            if (type === 'income') budgetSelect.value = '';
            if (type === 'expense') savingsSelect.value = '';
        });

        // Initial state
        updateVisibility(getSelectedCategoryType());
    });
    </script>
@endsection
