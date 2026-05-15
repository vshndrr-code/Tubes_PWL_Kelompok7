<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dompet Saya</h2>
                <p class="text-sm text-gray-500">Kelola akun Anda secara pribadi dan lihat saldo setiap dompet di sini.</p>
            </div>
            <div>
                <a href="{{ route('accounts.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/10 transition hover:bg-blue-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Akun
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 rounded-3xl border border-green-200 bg-green-50 px-6 py-4 text-green-700 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if($accounts->isEmpty())
                <div class="rounded-3xl border border-dashed border-gray-300 bg-white p-12 text-center shadow-sm">
                    <div class="mx-auto h-24 w-24 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                        <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10.5V19a2 2 0 002 2h14a2 2 0 002-2v-8.5M5 10.5L12 4l7 6.5M5 10.5h14" />
                        </svg>
                    </div>
                    <p class="text-gray-500 mb-4">Belum ada akun. Tambahkan akun untuk mulai mengelola saldo.</p>
                    <a href="{{ route('accounts.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Buat Akun Baru
                    </a>
                </div>
            @else
                <div class="grid gap-6 xl:grid-cols-[340px_minmax(0,1fr)]">
                    <aside class="rounded-3xl border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-200 px-6 py-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Pilih Dompet</p>
                            <h3 class="mt-3 text-xl font-semibold text-slate-900">Select Wallet</h3>
                            <p class="mt-2 text-sm text-slate-500">Pilih akun yang sudah pernah ditambahkan.</p>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="rounded-3xl bg-slate-50 p-5 shadow-sm">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Total Dompet</p>
                                        <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $accounts->count() }}</p>
                                    </div>
                                    <div class="rounded-2xl bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-sm">Aktif</div>
                                </div>
                                <p class="mt-4 text-sm text-slate-500">Semua akun yang sudah dibuat akan muncul di sini sebagai pilihan cepat.</p>
                            </div>

                            <div class="space-y-3">
                                @foreach($accounts as $account)
                                    @php
                                        $typeLabels = [
                                            'bank' => 'Bank',
                                            'cash' => 'Cash',
                                            'credit' => 'Kartu Kredit',
                                            'other' => 'E-wallet',
                                        ];

                                        $typeColors = [
                                            'bank' => 'bg-blue-500',
                                            'cash' => 'bg-green-500',
                                            'credit' => 'bg-purple-500',
                                            'other' => 'bg-gray-500',
                                        ];

                                        $accountLabel = $typeLabels[$account->type] ?? 'Dompet';
                                        $accountColor = $typeColors[$account->type] ?? 'bg-gray-500';
                                    @endphp

                                    <div class="group flex items-center justify-between gap-4 rounded-3xl border border-slate-200 bg-white px-4 py-4 shadow-sm transition hover:border-blue-300 hover:bg-blue-50">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $accountColor }} text-white shadow-sm">
                                                @switch($account->type)
                                                    @case('bank')
                                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                        </svg>
                                                        @break
                                                    @case('cash')
                                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                                        </svg>
                                                        @break
                                                    @case('credit')
                                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                        </svg>
                                                        @break
                                                    @default
                                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                        </svg>
                                                @endswitch
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-slate-900">{{ $account->name }}</p>
                                                <p class="text-xs text-slate-500">{{ $accountLabel }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <p class="text-sm font-semibold text-slate-900">Rp {{ number_format($account->balance, 0, ',', '.') }}</p>
                                            @if($loop->first)
                                                <span class="rounded-full bg-blue-600 px-3 py-1 text-xs font-semibold text-white">Selected</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </aside>

                    <section class="space-y-6">
                        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">
                            <div class="rounded-t-3xl bg-yellow-100 px-6 py-5">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-semibold text-yellow-900">Upgrade once, use lifetime</p>
                                        <p class="mt-2 text-sm text-yellow-800">Upgrading to PREMIUM helps you eliminate any barriers and difficulties in managing your cash flow, monthly budget, or future goals.</p>
                                    </div>
                                    <a href="#" class="inline-flex items-center gap-2 rounded-2xl bg-yellow-200 px-4 py-2 text-sm font-semibold text-yellow-900 hover:bg-yellow-300">Go PREMIUM</a>
                                </div>
                            </div>
                            <div class="px-6 py-6">
                                <div class="grid gap-4 sm:grid-cols-3">
                                    <div class="rounded-3xl bg-white p-5 text-center shadow-sm">
                                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Last Month</p>
                                        <p class="mt-3 text-xl font-semibold text-slate-900">Rp 0</p>
                                    </div>
                                    <div class="rounded-3xl bg-white p-5 text-center shadow-sm border border-blue-100">
                                        <p class="text-xs uppercase tracking-[0.2em] text-blue-500">This Month</p>
                                        <p class="mt-3 text-xl font-semibold text-slate-900">Rp 0</p>
                                    </div>
                                    <div class="rounded-3xl bg-white p-5 text-center shadow-sm">
                                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Future</p>
                                        <p class="mt-3 text-xl font-semibold text-slate-900">Rp 0</p>
                                    </div>
                                </div>
                                <div class="mt-8 rounded-3xl border border-dashed border-slate-200 bg-slate-50 p-10 text-center text-slate-500">
                                    <div class="text-5xl">^ ^</div>
                                    <p class="mt-4 text-sm">No transactions</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            @foreach($accounts as $account)
                                @php
                                    $typeLabels = [
                                        'bank' => 'Bank',
                                        'cash' => 'Cash',
                                        'credit' => 'Kartu Kredit',
                                        'other' => 'E-wallet',
                                    ];

                                    $typeColors = [
                                        'bank' => 'bg-blue-500',
                                        'cash' => 'bg-green-500',
                                        'credit' => 'bg-purple-500',
                                        'other' => 'bg-gray-500',
                                    ];

                                    $accountLabel = $typeLabels[$account->type] ?? 'Dompet';
                                    $accountColor = $typeColors[$account->type] ?? 'bg-gray-500';
                                @endphp

                                <div class="group relative overflow-hidden rounded-3xl bg-white border border-gray-200 shadow-lg transition hover:shadow-xl hover:-translate-y-1">
                                    <div class="absolute inset-x-0 top-0 h-20 {{ $accountColor }}"></div>
                                    <div class="relative p-6">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="h-12 w-12 rounded-full {{ $accountColor }} flex items-center justify-center shadow-lg">
                                                @switch($account->type)
                                                    @case('bank')
                                                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                        </svg>
                                                        @break
                                                    @case('cash')
                                                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                                        </svg>
                                                        @break
                                                    @case('credit')
                                                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                        </svg>
                                                        @break
                                                    @default
                                                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                        </svg>
                                                @endswitch
                                            </div>
                                            <div class="flex gap-2">
                                                <a href="{{ route('accounts.edit', $account) }}" class="p-2 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <form action="{{ route('accounts.destroy', $account) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Hapus akun ini?')" class="p-2 rounded-full bg-red-100 text-red-600 hover:bg-red-200 transition">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="space-y-2">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $account->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $accountLabel }}</p>
                                            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($account->balance, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
