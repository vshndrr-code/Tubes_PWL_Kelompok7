<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div class="mb-4">
                <a href="/" class="flex flex-col items-center space-y-2">
                    <div class="flex items-center justify-center">
                        <svg class="w-20 h-16" viewBox="0 0 320 240" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Arrow up-right (teal) -->
                            <path d="M 80 120 L 140 80 L 160 40 L 180 60 L 150 100 L 200 100 L 200 130 L 140 130 L 170 160 L 150 180 Z" fill="#1ABC9C" />
                            <!-- Wallet/Shield (dark blue) -->
                            <rect x="80" y="140" width="70" height="60" rx="12" fill="#001E3C" />
                            <circle cx="115" cy="175" r="6" fill="#1ABC9C" opacity="0.6" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">MOMA</h2>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
