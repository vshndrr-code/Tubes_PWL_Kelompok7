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

        // 3 goal templates mencakup semua kondisi donut chart
        $goalTemplates = [
            // ✅ Goal TERCAPAI (current >= target, status completed)
            [
                'name' => 'Liburan Akhir Tahun',
                'target_amount' => 2000000.00,
                'current_amount' => 2000000.00,
                'deadline' => now()->subDays(5)->format('Y-m-d'),
                'status' => 'completed',
            ],
            // 🟡 Goal BERJALAN (current > 0, progress ~45%)
            [
                'name' => 'Beli Sepatu Baru',
                'target_amount' => 750000.00,
                'current_amount' => 340000.00,
                'deadline' => now()->addWeeks(6)->format('Y-m-d'),
                'status' => 'active',
            ],
            // ⚪ Goal KOSONG (current = 0, belum mulai)
            [
                'name' => 'Beli Laptop Gaming',
                'target_amount' => 15000000.00,
                'current_amount' => 0.00,
                'deadline' => now()->addMonths(6)->format('Y-m-d'),
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