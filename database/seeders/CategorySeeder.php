<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return;
        }

        $categories = [
            [
                'name' => 'Makan',
                'type' => 'expense',
                'icon' => 'utensils',
                'color' => '#f97316',
            ],
            [
                'name' => 'Gaji',
                'type' => 'income',
                'icon' => 'money-bill-wave',
                'color' => '#22c55e',
            ],
            [
                'name' => 'Transport',
                'type' => 'expense',
                'icon' => 'car',
                'color' => '#0ea5e9',
            ],
            [
                'name' => 'Belanja',
                'type' => 'expense',
                'icon' => 'shopping-bag',
                'color' => '#8b5cf6',
            ],
            [
                'name' => 'Internet',
                'type' => 'expense',
                'icon' => 'wifi',
                'color' => '#14b8a6',
            ],
            [
                'name' => 'Hadiah',
                'type' => 'income',
                'icon' => 'gift',
                'color' => '#f43f5e',
            ],
            [
                'name' => 'Investasi',
                'type' => 'income',
                'icon' => 'chart-line',
                'color' => '#0f766e',
            ],
        ];

        foreach ($users as $user) {
            foreach ($categories as $category) {
                Category::updateOrCreate(
                    ['user_id' => $user->id, 'name' => $category['name']],
                    array_merge($category, ['user_id' => $user->id])
                );
            }
        }
    }
}
