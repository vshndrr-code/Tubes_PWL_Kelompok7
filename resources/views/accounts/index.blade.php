<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dompet Saya</h2>
                <p class="text-sm text-gray-500">Kelola akun Anda secara pribadi dan lihat saldo setiap dompet di sini.</p>
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
                </div>
            @else
                <div class="space-y-6">
                    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                        @php
                            $balanced = $accounts->sum('balance');
                        @endphp
                        <div class="rounded-3xl bg-gradient-to-br from-blue-600 to-blue-800 text-white p-6 shadow-xl">
                            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-100">Saldo Total</p>
                            <p class="mt-4 text-4xl font-black">Rp {{ number_format($balanced, 0, ',', '.') }}</p>
                        </div>
                        <div class="rounded-3xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white p-6 shadow-xl">
                            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-emerald-100">Jumlah Akun</p>
                            <p class="mt-4 text-4xl font-black">{{ $accounts->count() }}</p>
                        </div>
                        <div class="rounded-3xl bg-gradient-to-br from-purple-600 to-pink-600 text-white p-6 shadow-xl">
                            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-purple-100">Tipe Teratas</p>
                            <p class="mt-4 text-4xl font-black">{{ ucfirst($accounts->first()->type) }}</p>
                        </div>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach($accounts as $account)
                            @php
                                $typeLabels = [
                                    'bank' => 'Bank',
                                    'cash' => 'Cash',
                                    'credit' => 'Kartu Kredit',
                                    'other' => 'E-wallet',
                                ];

                                $typeColors = [
                                    'bank' => 'from-blue-600 to-blue-800',
                                    'cash' => 'from-emerald-500 to-emerald-700',
                                    'credit' => 'from-purple-600 to-pink-600',
                                    'other' => 'from-orange-500 to-red-600',
                                ];

                                $typeIconBg = [
                                    'bank' => 'bg-blue-400',
                                    'cash' => 'bg-emerald-400',
                                    'credit' => 'bg-purple-400',
                                    'other' => 'bg-orange-400',
                                ];

                                // Detect bank icons based on account name
                                $bankName = strtolower($account->name);
                                $isBankAccount = $account->type === 'bank';
                                
                                $bankIcons = [
                                    'bni' => '🏦',
                                    'bca' => '🏦',
                                    'mandiri' => '🏦',
                                    'permata' => '🏦',
                                    'cimb' => '🏦',
                                    'danamon' => '🏦',
                                    'anz' => '🏦',
                                ];

                                $bankIcon = '🏦';
                                if ($isBankAccount) {
                                    foreach ($bankIcons as $key => $icon) {
                                        if (strpos($bankName, $key) !== false) {
                                            $bankIcon = $icon;
                                            break;
                                        }
                                    }
                                }

                                $accountLabel = $typeLabels[$account->type] ?? 'Dompet';
                                $gradientColor = $typeColors[$account->type] ?? 'from-slate-600 to-slate-800';
                                $iconBg = $typeIconBg[$account->type] ?? 'bg-slate-400';
                            @endphp

                            <div class="group relative overflow-hidden rounded-3xl bg-white shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                                <div class="absolute inset-0 bg-gradient-to-br {{ $gradientColor }} opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                
                                <div class="relative px-6 py-6">
                                    <!-- Header -->
                                    <div class="flex items-start justify-between mb-6">
                                        <div>
                                            <p class="text-sm uppercase tracking-widest font-bold text-slate-500 group-hover:text-white transition-colors">{{ $accountLabel }}</p>
                                            <h3 class="mt-2 text-2xl font-black text-slate-900 group-hover:text-white transition-colors">{{ $account->name }}</h3>
                                        </div>
                                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl {{ $iconBg }} shadow-lg text-2xl group-hover:scale-110 transition-transform duration-300">
                                            @if($account->type === 'bank')
                                                {{ $bankIcon }}
                                            @elseif($account->type === 'cash')
                                                💵
                                            @elseif($account->type === 'credit')
                                                💳
                                            @else
                                                📱
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Balance -->
                                    <div class="mb-6">
                                        <p class="text-xs uppercase tracking-wider text-slate-500 group-hover:text-white transition-colors font-semibold">Saldo</p>
                                        <p class="mt-2 text-3xl font-black text-slate-900 group-hover:text-white transition-colors">Rp {{ number_format($account->balance, 0, ',', '.') }}</p>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                        <a href="{{ route('accounts.edit', $account) }}" class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-white/20 text-white hover:bg-white/30 py-2 px-3 text-sm font-semibold transition-colors backdrop-blur-sm">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7 0l7-7m0 0l-3-3m3 3L13 2"/></svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('accounts.destroy', $account) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Hapus akun ini?')" class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-500/30 text-white hover:bg-red-500/50 py-2 px-3 text-sm font-semibold transition-colors backdrop-blur-sm">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
