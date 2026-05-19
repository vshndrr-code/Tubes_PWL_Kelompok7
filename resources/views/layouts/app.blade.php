<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - MOMA</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <aside class="w-64 bg-white shadow-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-800">MOMA</h1>
                    <p class="text-sm text-gray-500">Management System</p>
                </div>

                <nav class="mt-6 space-y-2 px-4">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('accounts.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('accounts.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v2a1 1 0 001 1h14a1 1 0 001-1V6a2 2 0 00-2-2H4zm0 6a2 2 0 00-2 2v2a2 2 0 002 2h12a2 2 0 002-2v-2a2 2 0 00-2-2H4z"></path>
                        </svg>
                        <span>Accounts</span>
                    </a>

                    <a href="{{ route('transactions.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('transactions.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                        </svg>
                        <span>Transactions</span>
                    </a>
                </nav>

                <div class="absolute bottom-0 w-64 border-t px-4 py-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-2 rounded-lg text-red-700 hover:bg-red-50 transition-colors">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V4a1 1 0 00-1-1H3zm11 4.414l-4.707 4.707a1 1 0 01-1.414-1.414L12.586 7H6a1 1 0 000 2h6.586l-4.707 4.707a1 1 0 001.414 1.414L14.414 9.414a1 1 0 000-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col">
                <!-- Navbar -->
                <nav class="bg-white shadow">
                    <div class="px-6 py-4 flex justify-between items-center">
                        <div>
                            @isset($header)
                                {{ $header }}
                            @endisset
                        </div>
                        <div class="flex items-center space-x-4">
                            @if(isset($accounts) && $accounts->isNotEmpty())
                                @php
                                    $totalBalance = $accounts->sum('balance');
                                    $balanceLabel = $selectedAccount ? number_format($selectedAccount->balance, 0, ',', '.') : number_format($totalBalance, 0, ',', '.');
                                @endphp
                                <div class="relative group">
                                    <button type="button" class="inline-flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                                        <span class="inline-flex h-2.5 w-2.5 rounded-full bg-green-500"></span>
                                        <div class="flex-1 text-left">
                                            <div class="flex items-center gap-3">
                                                <span>{{ $selectedAccount ? $selectedAccount->name : 'Semua Akun' }}</span>
                                                <span class="rounded-full bg-white px-2 py-1 text-xs font-medium text-slate-500 shadow-sm">
                                                    {{ $selectedAccount ? ucfirst($selectedAccount->type) : $accounts->count().' akun' }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-slate-500 mt-1">Saldo: Rp {{ $balanceLabel }}</p>
                                        </div>
                                        <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    <div class="absolute right-0 z-20 mt-3 w-72 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl opacity-0 invisible transition-all duration-200 group-hover:opacity-100 group-hover:visible">
                                        <div class="border-b border-slate-200 px-4 py-4 bg-slate-50">
                                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Pilih akun</p>
                                            <p class="mt-2 text-sm font-semibold text-slate-900">Switch wallet</p>
                                        </div>
                                        <div class="divide-y divide-slate-200">
                                            <a href="{{ route('accounts.create') }}" class="flex items-center justify-between gap-3 px-4 py-4 hover:bg-slate-50 transition">
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-900">Tambah Akun</p>
                                                    <p class="text-xs text-slate-500">Buat akun baru</p>
                                                </div>
                                                <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('accounts.index') }}" class="flex items-center justify-between gap-3 px-4 py-4 hover:bg-slate-50 transition">
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-900">Semua Akun</p>
                                                    <p class="text-xs text-slate-500">Lihat semua dompet</p>
                                                </div>
                                                @if(is_null($selectedAccount))
                                                    <span class="rounded-full bg-blue-600 px-3 py-1 text-xs font-semibold text-white">Selected</span>
                                                @endif
                                            </a>
                                            @foreach($accounts as $account)
                                                <a href="{{ route('accounts.show', $account) }}" class="flex items-center justify-between gap-3 px-4 py-4 hover:bg-slate-50 transition">
                                                    <div>
                                                        <p class="text-sm font-semibold text-slate-900">{{ $account->name }}</p>
                                                        <p class="text-xs text-slate-500">{{ ucfirst($account->type) }}</p>
                                                    </div>
                                                    @if(optional($selectedAccount)->id === $account->id)
                                                        <span class="rounded-full bg-blue-600 px-3 py-1 text-xs font-semibold text-white">Selected</span>
                                                    @endif
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="text-sm text-gray-600">
                                Welcome, <span class="font-semibold">{{ Auth::user()->name }}</span>
                            </div>
                            <div class="w-10 h-10 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <main class="flex-1 overflow-auto">
                    @hasSection('content')
                        @yield('content')
                    @else
                        {{ $slot }}
                    @endif
                </main>
            </div>
        </div>
    </body>
</html>
