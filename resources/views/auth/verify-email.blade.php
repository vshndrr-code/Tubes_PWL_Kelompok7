<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Email - MOMA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js']) 
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-50">

    <div class="min-h-screen flex flex-col items-center justify-center p-6 py-12">
        <div class="w-full max-w-md">
            <!-- Logo & Header -->
            <div class="text-center mb-8 flex flex-col items-center">
                <div class="mb-6 flex flex-col items-center">
                    <!-- Logo Icon -->
                    <div class="flex items-center justify-center mb-3">
                        <svg class="w-24 h-20" viewBox="0 0 320 240" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Arrow up-right (teal) -->
                            <path d="M 80 120 L 140 80 L 160 40 L 180 60 L 150 100 L 200 100 L 200 130 L 140 130 L 170 160 L 150 180 Z" fill="#1ABC9C" />
                            <!-- Wallet/Shield (dark blue) -->
                            <rect x="80" y="140" width="70" height="60" rx="12" fill="#001E3C" />
                            <circle cx="115" cy="175" r="6" fill="#1ABC9C" opacity="0.6" />
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900">MOMA</h1>
                    <p class="text-xs text-emerald-600 font-semibold tracking-wide">FINANCIAL TRACKER</p>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Verify Email</h2>
                <p class="text-gray-500">Please verify your email address</p>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-8 sm:p-10">
                @if (session('status') == 'verification-link-sent')
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-6 py-4 text-emerald-700 shadow-sm text-sm font-medium">
                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                    </div>
                @endif

                <div class="mb-6 text-sm text-gray-600 leading-relaxed text-center">
                    {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                </div>

                <div class="space-y-4">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-3.5 px-6 rounded-2xl transition-colors duration-200 text-center">
                            {{ __('Resend Verification Email') }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}" class="flex justify-center">
                        @csrf
                        <button type="submit" class="text-sm font-semibold text-emerald-500 hover:text-emerald-600 transition-colors py-2">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
