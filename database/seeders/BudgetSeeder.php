<?php

namespace Database\Seeders;

use App\Models\Budgeting;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BudgetSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return;
        }

        $budgetTemplates = [
            ['name' => 'Makan', 'limit_amount' => 500000.00],
            ['name' => 'Transport', 'limit_amount' => 400000.00],
            ['name' => 'Belanja', 'limit_amount' => 600000.00],
            ['name' => 'Internet', 'limit_amount' => 250000.00],
        ];

        $month = now()->month;
        $year = now()->year;

        foreach ($users as $user) {
            foreach ($budgetTemplates as $budgetData) {
                $category = Category::where('user_id', $user->id)
                    ->where('name', $budgetData['name'])
                    ->first();

                if (! $category) {
                    continue;
                }

                Budgeting::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'category_id' => $category->id,
                        'month' => $month,
                        'year' => $year,
                    ],
                    [
                        'limit_amount' => $budgetData['limit_amount'],
                    ]
                );
            }
        }
    }
}
