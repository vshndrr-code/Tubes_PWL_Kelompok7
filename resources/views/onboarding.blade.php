<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOMA - Onboarding</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
    <div x-data="onboardingWizard()" class="min-h-screen flex flex-col">
        <!-- Stepper/Progress Indicator -->
        <div class="bg-white border-b border-gray-200 px-6 py-8 sm:px-12">
            <div class="max-w-4xl mx-auto">
                <div class="flex items-center justify-between gap-4">
                    <!-- Step 1 -->
                    <div class="flex flex-col items-center flex-shrink-0">
                        <div 
                            :style="currentStep >= 1 ? 'background-color: #10b981; color: white;' : 'background-color: #d1d5db; color: #6b7280;'"
                            class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg transition-all duration-300"
                        >
                            <span>
                                <span x-show="currentStep <= 1" style="">1</span>
                                <span x-show="currentStep > 1" style="">✓</span>
                            </span>
                        </div>
                        <p class="mt-3 text-xs font-semibold text-gray-700 text-center w-20">Select Base currency</p>
                    </div>

                    <!-- Connector Line 1 -->
                    <div 
                        :style="currentStep >= 2 ? 'background-color: #10b981;' : 'background-color: #d1d5db;'"
                        class="flex-1 h-1 transition-all duration-300"
                    style="min-width: 20px;"></div>

                    <!-- Step 2 -->
                    <div class="flex flex-col items-center flex-shrink-0">
                        <div 
                            :style="currentStep >= 2 ? 'background-color: #10b981; color: white;' : 'background-color: #d1d5db; color: #6b7280;'"
                            class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg transition-all duration-300"
                        >
                            <span>
                                <span x-show="currentStep <= 2" style="">2</span>
                                <span x-show="currentStep > 2" style="">✓</span>
                            </span>
                        </div>
                        <p class="mt-3 text-xs font-semibold text-gray-700 text-center w-20">Set up your cash balance</p>
                    </div>

                    <!-- Connector Line 2 -->
                    <div 
                        :style="currentStep >= 3 ? 'background-color: #10b981;' : 'background-color: #d1d5db;'"
                        class="flex-1 h-1 transition-all duration-300"
                    style="min-width: 20px;"></div>

                    <!-- Step 3 -->
                    <div class="flex flex-col items-center flex-shrink-0">
                        <div 
                            :style="currentStep >= 3 ? 'background-color: #10b981; color: white;' : 'background-color: #d1d5db; color: #6b7280;'"
                            class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg transition-all duration-300"
                        >
                            <span>
                                <span x-show="currentStep < 3" style="">3</span>
                                <span x-show="currentStep >= 3" style="">✓</span>
                            </span>
                        </div>
                        <p class="mt-3 text-xs font-semibold text-gray-700 text-center w-20">Success!</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Content -->
        <div class="flex-1 flex items-center justify-center px-4 py-8">
            <div class="w-full max-w-2xl">
                <!-- Step 1: Select Currency -->
                <div 
                    x-show="currentStep === 1"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-x-10"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-x-0"
                    x-transition:leave-end="opacity-0 transform -translate-x-10"
                    class="bg-white rounded-3xl border border-gray-200 shadow-sm p-8 sm:p-12"
                >
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Select Base Currency</h2>
                    <p class="text-gray-600 mb-8">Choose the primary currency for your account</p>

                    <div class="space-y-4">
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-2xl cursor-pointer hover:border-emerald-400 transition" :class="selectedCurrency === 'IDR' && 'border-emerald-500 bg-emerald-50'">
                            <input type="radio" x-model="selectedCurrency" value="IDR" class="w-5 h-5">
                            <span class="ml-4">
                                <span class="block font-semibold text-gray-900">Indonesian Rupiah (IDR)</span>
                                <span class="text-sm text-gray-500">Rp</span>
                            </span>
                        </label>

                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-2xl cursor-pointer hover:border-emerald-400 transition" :class="selectedCurrency === 'USD' && 'border-emerald-500 bg-emerald-50'">
                            <input type="radio" x-model="selectedCurrency" value="USD" class="w-5 h-5">
                            <span class="ml-4">
                                <span class="block font-semibold text-gray-900">US Dollar (USD)</span>
                                <span class="text-sm text-gray-500">$</span>
                            </span>
                        </label>

                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-2xl cursor-pointer hover:border-emerald-400 transition" :class="selectedCurrency === 'EUR' && 'border-emerald-500 bg-emerald-50'">
                            <input type="radio" x-model="selectedCurrency" value="EUR" class="w-5 h-5">
                            <span class="ml-4">
                                <span class="block font-semibold text-gray-900">Euro (EUR)</span>
                                <span class="text-sm text-gray-500">€</span>
                            </span>
                        </label>

                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-2xl cursor-pointer hover:border-emerald-400 transition" :class="selectedCurrency === 'SGD' && 'border-emerald-500 bg-emerald-50'">
                            <input type="radio" x-model="selectedCurrency" value="SGD" class="w-5 h-5">
                            <span class="ml-4">
                                <span class="block font-semibold text-gray-900">Singapore Dollar (SGD)</span>
                                <span class="text-sm text-gray-500">S$</span>
                            </span>
                        </label>
                    </div>

                    <button 
                        @click="nextStep()"
                        :disabled="!selectedCurrency"
                        class="mt-8 w-full bg-emerald-500 hover:bg-emerald-600 disabled:bg-gray-300 text-white font-semibold py-3 rounded-3xl transition duration-200"
                    >
                        Next
                    </button>
                </div>

                <!-- Step 2: Set Initial Balance -->
                <div 
                    x-show="currentStep === 2"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-x-10"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-x-0"
                    x-transition:leave-end="opacity-0 transform -translate-x-10"
                    class="bg-white rounded-3xl border border-gray-200 shadow-sm p-8 sm:p-12"
                >
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Set up your cash balance</h2>
                    <p class="text-gray-600 mb-8">Enter your initial account balance</p>

                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Initial Balance</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-2xl font-semibold text-gray-500" x-text="getCurrencySymbol()"></span>
                            <input 
                                type="number" 
                                x-model.number="initialBalance"
                                placeholder="0"
                                class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-2xl focus:border-emerald-500 focus:outline-none text-xl font-semibold"
                            >
                        </div>
                    </div>

                    <p class="text-gray-600 text-sm mb-8">
                        Your account will be created with <strong x-text="getCurrencySymbol() + ' ' + (initialBalance || 0).toLocaleString()"></strong>
                    </p>

                    <div class="flex gap-4">
                        <button 
                            @click="prevStep()"
                            class="flex-1 border-2 border-gray-300 hover:border-gray-400 text-gray-700 font-semibold py-3 rounded-3xl transition duration-200"
                        >
                            Back
                        </button>
                        <button 
                            @click="nextStep()"
                            class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-3 rounded-3xl transition duration-200"
                        >
                            Create Account
                        </button>
                    </div>
                </div>

                <!-- Step 3: Success -->
                <div 
                    x-show="currentStep === 3"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-x-10"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-x-0"
                    x-transition:leave-end="opacity-0 transform -translate-x-10"
                    class="bg-white rounded-3xl border border-gray-200 shadow-sm p-8 sm:p-12 text-center"
                >
                    <!-- Success Icon -->
                    <div class="flex justify-center mb-12" style="padding: 20px;">
                        <div class="relative" style="width: 160px; height: 160px;">
                            <div class="absolute inset-0 rounded-full animate-pulse" style="background-color: #d1fae5; width: 160px; height: 160px;"></div>
                            <div class="absolute inset-0 rounded-full flex items-center justify-center" style="background-color: #10b981; width: 160px; height: 160px;">
                                <div style="font-size: 80px; line-height: 1; color: white; font-weight: bold;">✓</div>
                            </div>
                        </div>
                    </div>

                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Success!</h2>
                    <p class="text-gray-500 text-lg mb-8">
                        Your very first account has been created. Now<br>
                        continue to dashboard and start tracking.
                    </p>

                    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6 mb-8">
                        <p class="text-sm text-gray-600">Account Summary:</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900">
                            <span x-text="getCurrencySymbol()"></span>
                            <span x-text="(initialBalance || 0).toLocaleString()"></span>
                        </p>
                        <p class="text-sm text-gray-500 mt-1" x-text="'Currency: ' + selectedCurrency"></p>
                    </div>

                    <form method="POST" action="{{ route('onboarding.complete') }}">
                        @csrf
                        <input type="hidden" name="currency" :value="selectedCurrency">
                        <input type="hidden" name="initial_balance" :value="initialBalance || 0">
                        <button
                            type="submit"
                            class="inline-block w-full bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-3 rounded-full transition duration-200"
                        >
                            Continue to Dashboard
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function onboardingWizard() {
            return {
                currentStep: 1,
                selectedCurrency: 'IDR',
                initialBalance: 0,

                nextStep() {
                    if (this.currentStep < 3) {
                        this.currentStep++;
                    }
                },

                prevStep() {
                    if (this.currentStep > 1) {
                        this.currentStep--;
                    }
                },

                getCurrencySymbol() {
                    const symbols = {
                        'IDR': 'Rp',
                        'USD': '$',
                        'EUR': '€',
                        'SGD': 'S$'
                    };
                    return symbols[this.selectedCurrency] || 'Rp';
                }
            }
        }
    </script>
</body>
</html>
