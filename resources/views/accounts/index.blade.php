<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dompet Saya</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola akun Anda secara pribadi dan lihat saldo setiap dompet di sini.</p>
            </div>
            <a href="{{ route('accounts.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Akun
            </a>
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
            <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center shadow-sm">
                    <div class="mx-auto h-20 w-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                        <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10.5V19a2 2 0 002 2h14a2 2 0 002-2v-8.5M5 10.5L12 4l7 6.5M5 10.5h14" />
                        </svg>
                    </div>
                    <p class="text-gray-600 mb-6 text-sm">Belum ada akun. Tambahkan akun untuk mulai mengelola saldo.</p>
                    <a href="{{ route('accounts.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Akun Pertamamu
                    </a>
                </div>
            @else
                <div class="space-y-6">
                    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                        @php
                            $balanced = $accounts->sum('balance');
                        @endphp
                        <div class="rounded-xl bg-blue-600 text-white p-5 shadow border border-blue-700/20">
                            <p class="text-xs font-semibold uppercase tracking-wide text-blue-100">Saldo Total</p>
                            <p class="mt-2 text-3xl font-bold">Rp {{ number_format($balanced, 0, ',', '.') }}</p>
                        </div>
                        <div class="rounded-xl bg-emerald-600 text-white p-5 shadow border border-emerald-700/20">
                            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-100">Jumlah Akun</p>
                            <p class="mt-2 text-3xl font-bold">{{ $accounts->count() }}</p>
                        </div>
                        <div class="rounded-xl bg-purple-600 text-white p-5 shadow border border-purple-700/20">
                            <p class="text-xs font-semibold uppercase tracking-wide text-purple-100">Tipe Teratas</p>
                            <p class="mt-2 text-3xl font-bold">{{ ucfirst($accounts->first()->type) }}</p>
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
                                    'bank' => 'bg-blue-600',
                                    'cash' => 'bg-emerald-500',
                                    'credit' => 'bg-purple-600',
                                    'other' => 'bg-orange-500',
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

                            <div class="group relative overflow-hidden rounded-2xl bg-white shadow-sm hover:shadow-md transition-all duration-300 border border-gray-200/50 hover:-translate-y-0.5">
                                
                                <div class="relative px-5 py-5">
                                    <!-- Header -->
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <p class="text-xs uppercase tracking-widest font-medium text-gray-500">{{ $accountLabel }}</p>
                                            <h3 class="mt-1 text-lg font-bold text-gray-900">{{ $account->name }}</h3>
                                        </div>
                                        <div class="flex h-12 w-12 items-center justify-center rounded-xl {{ $iconBg }} shadow text-xl transition-transform duration-300">
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
                                    <div class="mb-4 pb-4 border-b border-gray-100">
                                        <p class="text-xs uppercase tracking-wider text-gray-500 font-medium">Saldo</p>
                                        <p class="mt-1 text-2xl font-bold text-gray-900">Rp {{ number_format($account->balance, 0, ',', '.') }}</p>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('accounts.edit', $account) }}" class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 py-2 px-3 text-sm font-medium transition">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7 0l7-7m0 0l-3-3m3 3L13 2"/></svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('accounts.destroy', $account) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Hapus akun ini?')" class="inline-flex items-center justify-center gap-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 py-2 px-3 text-sm font-medium transition">
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
