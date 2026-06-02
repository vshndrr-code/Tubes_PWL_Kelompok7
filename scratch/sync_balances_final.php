<?php

// Bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Account;
use App\Models\Transaction;

echo "Synchronizing Account Balances...\n";
foreach (Account::all() as $account) {
    $startingBalance = $account->balance;
    
    // Fetch all transactions associated with this account
    $transactions = Transaction::where('account_id', $account->id)->get();
    
    $newBalance = $startingBalance;
    foreach ($transactions as $transaction) {
        if ($transaction->type === 'income') {
            $newBalance += $transaction->amount;
        } else {
            $newBalance -= $transaction->amount;
        }
    }
    
    echo "- Account ID: {$account->id} ({$account->name}):\n";
    echo "  Original/Starting Balance: Rp " . number_format($startingBalance, 0, ',', '.') . "\n";
    echo "  Transactions Applied: " . $transactions->count() . "\n";
    echo "  New Synchronized Balance: Rp " . number_format($newBalance, 0, ',', '.') . "\n";
    
    $account->balance = $newBalance;
    $account->save();
}

echo "Synchronization complete!\n";
