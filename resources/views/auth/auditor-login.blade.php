<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Portal Auditor - MOMA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js']) 
</head>
<body class="font-sans text-slate-900 antialiased bg-[#f6f7f9]">

    <div class="min-h-screen flex flex-col items-center justify-center p-6 py-12">
        <div class="w-full max-w-md">
            <!-- Logo & Header -->
            <div class="text-center mb-8 flex flex-col items-center">
                <div class="mb-6 flex flex-col items-center">
                    <!-- Logo Icon -->
                    <div class="flex items-center justify-center mb-3">
                        <svg class="w-24 h-20" viewBox="0 0 320 240" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Arrow up-right (teal) -->
                            <path d="M 80 120 L 140 80 L 160 40 L 180 60 L 150 100 L 200 100 L 200 130 L 140 130 L 170 160 L 150 180 Z" fill="#059669" />
                            <!-- Wallet/Shield (dark blue) -->
                            <rect x="80" y="140" width="70" height="60" rx="12" fill="#020617" />
                            <circle cx="115" cy="175" r="6" fill="#10b981" opacity="0.6" />
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-slate-950">MOMA</h1>
                    <p class="text-xs text-emerald-600 font-semibold tracking-wider">AUDITOR PORTAL</p>
                </div>
                <h2 class="text-3xl font-bold text-slate-950 mb-2">Portal Auditor</h2>
                <p class="text-slate-500 text-sm">Masuk untuk memantau keuangan dan moderasi sistem</p>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-8 sm:p-10">
                @if (session('status'))
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-6 py-4 text-emerald-700 shadow-sm text-sm font-medium">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('auditor.login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Email Auditor</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                            class="w-full px-5 py-3.5 border-2 border-slate-200 rounded-2xl focus:border-emerald-500 focus:ring-emerald-500 focus:outline-none transition-all text-slate-900 placeholder-slate-400 text-sm"
                            placeholder="auditor@moma.com">
                        @error('email')
                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="password" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Kata Sandi</label>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="w-full px-5 py-3.5 border-2 border-slate-200 rounded-2xl focus:border-emerald-500 focus:ring-emerald-500 focus:outline-none transition-all text-slate-900 placeholder-slate-400 text-sm"
                            placeholder="••••••••">
                        @error('password')
                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="w-5 h-5 border-2 border-slate-300 rounded text-emerald-600 focus:ring-emerald-500 transition-colors cursor-pointer">
                        <label for="remember_me" class="ml-3 block text-sm font-semibold text-slate-600 cursor-pointer">
                            Ingat saya di perangkat ini
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3.5 px-6 rounded-2xl transition-all duration-200 shadow-md shadow-emerald-500/10 hover:shadow-lg">
                            Masuk Portal Auditor
                        </button>
                    </div>
                </form>
            </div>

            <!-- Back to User Login -->
            <div class="mt-8 text-center flex items-center justify-center gap-2">
                <p class="text-sm font-medium text-slate-500">
                    Bukan Auditor?
                </p>
                <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-950 transition-colors px-4 py-2 bg-white rounded-xl shadow-sm border border-slate-200">
                    Login User Biasa
                </a>
            </div>
        </div>
    </div>
</body>
</html>
