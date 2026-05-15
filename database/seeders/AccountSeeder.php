<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return;
        }

        $accounts = [
            [
                'name' => 'Bank BCA',
                'type' => 'bank',
                'balance' => 1250000.00,
            ],
            [
                'name' => 'Cash',
                'type' => 'cash',
                'balance' => 250000.00,
            ],
            [
                'name' => 'Bank Mandiri',
                'type' => 'bank',
                'balance' => 860000.00,
            ],
            [
                'name' => 'OVO',
                'type' => 'other',
                'balance' => 520000.00,
            ],
            [
                'name' => 'DANA',
                'type' => 'other',
                'balance' => 310000.00,
            ],
            [
                'name' => 'Kartu Kredit BNI',
                'type' => 'credit',
                'balance' => 0.00,
            ],
        ];

        foreach ($users as $user) {
            foreach ($accounts as $account) {
                Account::updateOrCreate(
                    ['user_id' => $user->id, 'name' => $account['name']],
                    array_merge($account, ['user_id' => $user->id])
                );
            }
        }
    }
}
