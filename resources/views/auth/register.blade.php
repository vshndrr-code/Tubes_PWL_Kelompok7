<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - MOMA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-50">

    <div class="min-h-screen flex flex-col items-center justify-center p-6 py-12">
        <div class="w-full max-w-md">
            <!-- Logo & Header -->
            <div class="text-center mb-8 flex flex-col items-center">
                <div class="w-16 h-16 bg-white rounded-3xl flex items-center justify-center shadow-sm border border-gray-100 mb-6">
                    <span class="text-emerald-500 font-black text-3xl">M</span>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Create an Account</h2>
                <p class="text-gray-500">Set up your MOMA profile</p>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-8 sm:p-10">
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                            class="w-full px-5 py-3 border-2 border-gray-200 rounded-2xl focus:border-emerald-500 focus:outline-none transition-colors text-gray-900 placeholder-gray-400"
                            placeholder="Your full name">
                        @error('name')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                            class="w-full px-5 py-3 border-2 border-gray-200 rounded-2xl focus:border-emerald-500 focus:outline-none transition-colors text-gray-900 placeholder-gray-400"
                            placeholder="you@example.com">
                        @error('email')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                            class="w-full px-5 py-3 border-2 border-gray-200 rounded-2xl focus:border-emerald-500 focus:outline-none transition-colors text-gray-900 placeholder-gray-400"
                            placeholder="••••••••">
                        @error('password')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                            class="w-full px-5 py-3 border-2 border-gray-200 rounded-2xl focus:border-emerald-500 focus:outline-none transition-colors text-gray-900 placeholder-gray-400"
                            placeholder="••••••••">
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-3">
                        <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-3.5 px-6 rounded-2xl transition-colors duration-200">
                            Register
                        </button>
                    </div>
                </form>
            </div>

            <div class="mt-8 text-center flex items-center justify-center gap-2">
                <p class="text-sm font-medium text-gray-500">
                    Already registered?
                </p>
                <a href="{{ route('login') }}" class="text-sm font-semibold text-emerald-500 hover:text-emerald-600 transition-colors px-4 py-2 bg-white rounded-xl shadow-sm border border-gray-200">
                    Sign in here
                </a>
            </div>
        </div>
    </div>
</body>
</html>
