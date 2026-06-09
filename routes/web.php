<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BudgetingController;
use App\Http\Controllers\SavingsGoalsController;
use App\Http\Controllers\NotificationController;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Budgeting;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/dashboard', function () {
    $user = Auth::user();
    
    // Get user's accounts
    $accountItems = Account::where('user_id', $user->id)->get();
    
    // Get user's transactions
    $transactionItems = Transaction::where('user_id', $user->id)
        ->orderBy('transaction_date', 'desc')
        ->get();
    
    // Get user's budgets
    $budgetings = Budgeting::where('user_id', $user->id)->get();
    
    // Get recent transactions
    $recentTransactions = Transaction::where('user_id', $user->id)
        ->orderBy('transaction_date', 'desc')
        ->limit(6)
        ->get();
    
    // Get accounts data
    $accounts = Account::where('user_id', $user->id)->get();
    $selectedAccount = null; // or get from request if needed
    
    return view('dashboard', [
        'accountItems' => $accountItems,
        'transactionItems' => $transactionItems,
        'budgetings' => $budgetings,
        'recentTransactions' => $recentTransactions,
        'accounts' => $accounts,
        'selectedAccount' => $selectedAccount,
    ]);
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

    Route::get('accounts/transfer', [AccountController::class, 'transferForm'])->name('accounts.transfer');
    Route::post('accounts/transfer', [AccountController::class, 'processTransfer'])->name('accounts.transfer.store');
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

    Route::patch('/accounts/{account}/pin', [AccountController::class, 'togglePin'])->name('accounts.pin');
    Route::patch('/accounts/{account}/archive', [AccountController::class, 'archive'])->name('accounts.archive');
    Route::patch('/accounts/{account}/restore', [AccountController::class, 'restore'])->name('accounts.restore');

    Route::resource('budgetings', BudgetingController::class);
    Route::resource('savings-goals', SavingsGoalsController::class);
    Route::resource('notifications', NotificationController::class);
});

require __DIR__ . '/auth.php';
