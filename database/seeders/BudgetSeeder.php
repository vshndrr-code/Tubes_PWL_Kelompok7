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
        // Only seed budgets for non-auditor users
        $users = User::where('role', '!=', 'auditor')->get();

        if ($users->isEmpty()) {
            return;
        }

        $budgetTemplates = [
            ['name' => 'Budget Makan', 'category_name' => 'Makan & Minuman', 'limit_amount' => 500000.00],
            ['name' => 'Budget Transport', 'category_name' => 'Transportasi', 'limit_amount' => 400000.00],
            ['name' => 'Budget Belanja', 'category_name' => 'Belanja Barang', 'limit_amount' => 600000.00],
            ['name' => 'Budget Internet', 'category_name' => 'Internet & Telepon', 'limit_amount' => 250000.00],
        ];

        $month = now()->month;
        $year = now()->year;

        foreach ($users as $user) {
            foreach ($budgetTemplates as $budgetData) {
                // Look for global categories (user_id = null) or user-owned categories
                $category = Category::where(function ($query) use ($user, $budgetData) {
                        $query->whereNull('user_id')
                              ->orWhere('user_id', $user->id);
                    })
                    ->where('name', $budgetData['category_name'])
                    ->first();

                Budgeting::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'name' => $budgetData['name'],
                        'month' => $month,
                        'year' => $year,
                    ],
                    [
                        'category_id' => $category?->id,
                        'limit_amount' => $budgetData['limit_amount'],
                    ]
                );
            }
        }
    }
}
