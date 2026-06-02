<?php

// Bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Account;
use App\Models\Transaction;

echo "Current Accounts and Balances:\n";
foreach (Account::all() as $account) {
    echo "- Account ID: {$account->id}, Name: {$account->name}, Current Balance: {$account->balance}\n";
    
    // Let's see the transactions associated with this account
    $transactions = Transaction::where('account_id', $account->id)->get();
    echo "  Transactions count: " . $transactions->count() . "\n";
    
    $incomeSum = $transactions->where('type', 'income')->sum('amount');
    $expenseSum = $transactions->where('type', 'expense')->sum('amount');
    
    echo "  Total Income: {$incomeSum}\n";
    echo "  Total Expense: {$expenseSum}\n";
}
