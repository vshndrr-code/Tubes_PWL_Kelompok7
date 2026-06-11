<?php

namespace Database\Seeders;

use App\Models\SavingsGoals;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GoalSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Only seed goals for non-auditor users
        $users = User::where('role', '!=', 'auditor')->get();

        if ($users->isEmpty()) {
            return;
        }

        $goalTemplates = [
            [
                'name' => 'Beli Sepatu',
                'target_amount' => 750000.00,
                'current_amount' => 150000.00,
                'deadline' => now()->addWeeks(6)->format('Y-m-d'),
                'status' => 'active',
            ],
            [
                'name' => 'Beli Laptop',
                'target_amount' => 5500000.00,
                'current_amount' => 950000.00,
                'deadline' => now()->addMonths(3)->format('Y-m-d'),
                'status' => 'active',
            ],
        ];

        foreach ($users as $user) {
            foreach ($goalTemplates as $goalData) {
                SavingsGoals::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'name' => $goalData['name'],
                    ],
                    array_merge($goalData, ['user_id' => $user->id])
                );
            }
        }
    }
}
