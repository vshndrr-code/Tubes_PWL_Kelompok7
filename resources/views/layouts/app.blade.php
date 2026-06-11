<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - MOMA</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>

<body class="font-sans antialiased" x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false">
    <div class="min-h-screen flex bg-slate-50">
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen"
             x-transition:enter="transition-opacity ease-linear duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-slate-900/50 z-30 lg:hidden"
             x-cloak></div>

        <!-- Sidebar -->
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-40 w-64 bg-white shadow-lg transform transition-transform duration-200 ease-in-out lg:translate-x-0 flex flex-col">
            <div class="p-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">MOMA</h1>
                    <p class="text-sm text-gray-500">Management System</p>
                </div>
                <button @click="sidebarOpen = false"
                        class="lg:hidden inline-flex items-center justify-center rounded-md p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-500"
                        aria-label="Close sidebar">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="mt-2 space-y-1 px-4 flex-1 overflow-y-auto">
                @if(auth()->check() && !auth()->user()->isAuditor())
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                            </path>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('accounts.index') }}"
                        class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('accounts.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M4 4a2 2 0 00-2 2v2a1 1 0 001 1h14a1 1 0 001-1V6a2 2 0 00-2-2H4zm0 6a2 2 0 00-2 2v2a2 2 0 002 2h12a2 2 0 002-2v-2a2 2 0 00-2-2H4z">
                            </path>
                        </svg>
                        <span>Accounts</span>
                    </a>

                    <a href="{{ route('transactions.index') }}"
                        class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('transactions.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z">
                            </path>
                        </svg>
                        <span>Transactions</span>
                    </a>

                    <a href="{{ route('budgetings.index') }}"
                        class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('budgetings.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 3h14v2H3V3zm0 5h10v2H3V8zm0 5h6v2H3v-2z" />
                        </svg>
                        <span>Budgetings</span>
                    </a>

                    <a href="{{ route('savings-goals.index') }}"
                        class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('savings-goals.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12zm1-9H9v4h2V7z" />
                        </svg>
                        <span>Savings Goals</span>
                    </a>

                    <a href="{{ route('tags.index') }}"
                        class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('tags.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M17.707 9.293l-7-7A1 1 0 0010 2H4a2 2 0 00-2 2v6a1 1 0 00.293.707l7 7a1 1 0 001.414 0l7-7a1 1 0 000-1.414zM9 7a2 2 0 11-4 0 2 2 0 014 0z" clip-rule="evenodd" />
                        </svg>
                        <span>Tags</span>
                    </a>
                @endif

                @if(auth()->check() && auth()->user()->isAuditor())
                    <div class="pt-4 mt-4 border-t border-gray-100">
                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Auditor Panel</p>
                        
                        <a href="{{ route('auditor.dashboard') }}"
                            class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('auditor.dashboard') ? 'bg-emerald-100 text-emerald-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span>Dashboard Auditor</span>
                        </a>

                        <a href="{{ route('auditor.categories.index') }}"
                            class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('auditor.categories.*') ? 'bg-emerald-100 text-emerald-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                            </svg>
                            <span>Moderasi Kategori</span>
                        </a>

                        <a href="{{ route('auditor.tags.index') }}"
                            class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('auditor.tags.*') ? 'bg-emerald-100 text-emerald-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span>Moderasi Tag</span>
                        </a>
                    </div>
                @endif
            </nav>

            <div class="border-t px-4 py-4 space-y-2 bg-white">
                <a href="{{ route('profile.edit') }}"
                    class="w-full flex items-center px-4 py-2 rounded-lg transition-colors font-medium {{ request()->routeIs('profile.edit') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>Edit Profile</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V4a1 1 0 00-1-1H3zm11 4.414l-4.707 4.707a1 1 0 01-1.414-1.414L12.586 7H6a1 1 0 000 2h6.586l-4.707 4.707a1 1 0 001.414 1.414L14.414 9.414a1 1 0 000-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
                <div x-data>
                    <button x-on:click.prevent="$dispatch('open-modal', 'global-confirm-user-deletion')"
                        class="w-full flex items-center px-4 py-2 rounded-lg text-red-600 hover:bg-red-50 transition-colors font-medium">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Delete Account</span>
                    </button>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="lg:ml-64 flex-1 flex flex-col min-w-0 overflow-x-hidden">
            <!-- Navbar -->
            <nav class="sticky top-0 z-20 bg-white shadow">
                <div class="px-4 sm:px-6 py-3 sm:py-4 flex justify-between items-center gap-2 sm:gap-3">
                    <button @click="sidebarOpen = true"
                            class="lg:hidden inline-flex items-center justify-center rounded-md p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                            aria-label="Open sidebar">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="flex-1 min-w-0">
                        @isset($header)
                            {{ $header }}
                        @endisset
                    </div>
                    <div class="flex items-center gap-2 sm:space-x-4">
                        @if (isset($accounts) && $accounts->isNotEmpty())
                            @php
                                $totalBalance = $accounts->sum('balance');
                                $balanceLabel = $selectedAccount
                                    ? number_format($selectedAccount->balance, 0, ',', '.')
                                    : number_format($totalBalance, 0, ',', '.');
                            @endphp
                            <div class="relative group">
                                <button type="button"
                                    class="inline-flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                                    <span class="inline-flex h-2.5 w-2.5 rounded-full bg-green-500"></span>
                                    <div class="flex-1 text-left">
                                        <div class="flex items-center gap-3">
                                            <span>{{ $selectedAccount ? $selectedAccount->name : 'Semua Akun' }}</span>
                                            <span
                                                class="rounded-full bg-white px-2 py-1 text-xs font-medium text-slate-500 shadow-sm">
                                                {{ $selectedAccount ? ucfirst($selectedAccount->type) : $accounts->count() . ' akun' }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1">Saldo: {{ $currencySymbol }} {{ $balanceLabel }}</p>
                                    </div>
                                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div
                                    class="absolute right-0 top-full z-50 mt-2 w-72 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl opacity-0 invisible transition-all duration-200 group-hover:opacity-100 group-hover:visible">
                                    <div class="border-b border-slate-200 px-4 py-4 bg-slate-50">
                                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Pilih akun</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-900">Switch wallet</p>
                                    </div>
                                    <div class="divide-y divide-slate-200">
                                        <a href="{{ route('accounts.create') }}"
                                            class="flex items-center justify-between gap-3 px-4 py-4 hover:bg-slate-50 transition">
                                            <div>
                                                <p class="text-sm font-semibold text-slate-900">Tambah Akun</p>
                                                <p class="text-xs text-slate-500">Buat akun baru</p>
                                            </div>
                                            <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('accounts.index') }}"
                                            class="flex items-center justify-between gap-3 px-4 py-4 hover:bg-slate-50 transition">
                                            <div>
                                                <p class="text-sm font-semibold text-slate-900">Semua Akun</p>
                                                <p class="text-xs text-slate-500">Lihat semua dompet</p>
                                            </div>
                                            @if (is_null($selectedAccount))
                                                <span
                                                    class="rounded-full bg-blue-600 px-3 py-1 text-xs font-semibold text-white">Selected</span>
                                            @endif
                                        </a>
                                        @foreach ($accounts as $account)
                                            <a href="{{ route('accounts.show', $account) }}"
                                                class="flex items-center justify-between gap-3 px-4 py-4 hover:bg-slate-50 transition">
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-900">
                                                        {{ $account->name }}</p>
                                                    <p class="text-xs text-slate-500">
                                                        {{ ucfirst($account->type) }} · {{ $currencySymbol }} {{ number_format($account->balance, 0, ',', '.') }}
                                                    </p>
                                                </div>
                                                @if (optional($selectedAccount)->id === $account->id)
                                                    <span
                                                        class="rounded-full bg-blue-600 px-3 py-1 text-xs font-semibold text-white">Selected</span>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-3 hover:opacity-80 transition focus:outline-none text-left">
                            <div class="text-sm text-gray-600 hidden sm:block">
                                <span class="block text-[10px] text-slate-400 font-medium uppercase tracking-[0.1em] leading-none">Welcome</span>
                                <span class="font-semibold text-slate-800">{{ Auth::user()->name }}</span>
                            </div>
                            <div class="w-10 h-10 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold shadow-sm ring-2 ring-white hover:ring-blue-100 transition duration-150">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </a>
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

    <x-modal name="global-confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="global_password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input id="global_password" name="password" type="password" class="mt-1 block w-3/4"
                    placeholder="{{ __('Password') }}" />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>

    <script>
    document.addEventListener('DOMContentLoaded', function(){
        const currencyNames = ['amount','target_amount','current_amount','limit_amount'];

        function sanitizeVal(v){
            if (v === null || v === undefined) return v;
            v = String(v).trim();
            if (v === '') return v;
            v = v.replace(/\s+/g,'');
            const hasDot = v.indexOf('.') !== -1;
            const hasComma = v.indexOf(',') !== -1;
            if (hasDot && hasComma) {
                // assume dot = thousands, comma = decimal
                v = v.replace(/\./g,'').replace(',','.');
            } else if (hasDot && !hasComma) {
                // if dots look like thousands separators (groups of 3), remove them
                if (/\.\d{3}(?:\.\d{3})*$/.test(v)) {
                    v = v.replace(/\./g,'');
                }
                // otherwise keep dot as decimal separator
            } else if (!hasDot && hasComma) {
                // comma used as decimal separator -> convert to dot
                v = v.replace(/,/g,'.');
            }
            // strip any non-digit/decimal/minus chars
            v = v.replace(/[^0-9.\-]/g,'');
            return v;
        }

        function attach(){
            currencyNames.forEach(name => {
                document.querySelectorAll('input[name="'+name+'"]').forEach(input => {
                    // skip number-type inputs; they have native formatting
                    if (input.type === 'number') {
                        console.log('Skipping number input:', name, '- type:', input.type);
                        return;
                    }

                    console.log('Attaching blur handler to:', name, '- type:', input.type);
                    // on blur sanitize
                    input.addEventListener('blur', function(){
                        console.log('Blur event on', name, '- before:', this.value);
                        const s = sanitizeVal(this.value);
                        console.log('Blur event on', name, '- after sanitize:', s);
                        this.value = s;
                    });

                    // ensure form submit sanitizes too
                    const form = input.closest('form');
                    if (form && !form._currencyAttached) {
                        form.addEventListener('submit', function(){
                            currencyNames.forEach(n => {
                                const inp = this.querySelector('input[name="'+n+'"]');
                                if (inp && inp.type !== 'number') {
                                    inp.value = sanitizeVal(inp.value);
                                }
                            });
                        });
                        form._currencyAttached = true;
                    }
                });
            });
        }

        attach();
        const mo = new MutationObserver(attach);
        mo.observe(document.body, { childList: true, subtree: true });
    });
    </script>

    @stack('scripts')
</body>

</html>
