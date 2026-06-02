<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/onboarding', function () {
    return view('onboarding');
})->middleware(['auth', 'verified'])->name('onboarding');

Route::post('/onboarding/complete', [OnboardingController::class, 'complete'])
    ->middleware(['auth', 'verified'])
    ->name('onboarding.complete');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('accounts', AccountController::class);
    Route::get('transactions/recurring', [TransactionController::class, 'createRecurring'])->name('transactions.createRecurring');
    Route::post('transactions/recurring', [TransactionController::class, 'storeRecurring'])->name('transactions.storeRecurring');
    Route::get('transactions/recurring/{recurringTransaction}', [TransactionController::class, 'showRecurring'])->name('transactions.showRecurring');
    Route::get('transactions/recurring/{recurringTransaction}/edit', [TransactionController::class, 'editRecurring'])->name('transactions.editRecurring');
    Route::put('transactions/recurring/{recurringTransaction}', [TransactionController::class, 'updateRecurring'])->name('transactions.updateRecurring');
    Route::delete('transactions/recurring/{recurringTransaction}', [TransactionController::class, 'destroyRecurring'])->name('transactions.destroyRecurring');
    Route::resource('transactions', TransactionController::class);
    
    Route::get('accounts/{account}/transactions', [TransactionController::class, 'getByAccount'])->name('transactions.byAccount');
    Route::get('categories/{category}/transactions', [TransactionController::class, 'getByCategory'])->name('transactions.byCategory');
});

require __DIR__.'/auth.php';
