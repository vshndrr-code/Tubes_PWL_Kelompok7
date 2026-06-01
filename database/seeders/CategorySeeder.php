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
            // Income Categories
            [
                'name' => 'Gaji',
                'type' => 'income',
                'icon' => 'money-bill-wave',
                'color' => '#22c55e',
            ],
            [
                'name' => 'Bonus',
                'type' => 'income',
                'icon' => 'gift',
                'color' => '#f59e0b',
            ],
            [
                'name' => 'Freelance',
                'type' => 'income',
                'icon' => 'briefcase',
                'color' => '#3b82f6',
            ],
            [
                'name' => 'Investasi Return',
                'type' => 'income',
                'icon' => 'chart-line',
                'color' => '#0f766e',
            ],
            [
                'name' => 'Penjualan Barang',
                'type' => 'income',
                'icon' => 'tag',
                'color' => '#7c3aed',
            ],
            [
                'name' => 'Cashback & Refund',
                'type' => 'income',
                'icon' => 'redo',
                'color' => '#ec4899',
            ],

            // Expense Categories - Food & Dining
            [
                'name' => 'Makan & Minuman',
                'type' => 'expense',
                'icon' => 'utensils',
                'color' => '#f97316',
            ],
            [
                'name' => 'Kopi & Snack',
                'type' => 'expense',
                'icon' => 'coffee',
                'color' => '#d97706',
            ],
            [
                'name' => 'Restoran',
                'type' => 'expense',
                'icon' => 'drumstick-bite',
                'color' => '#ea580c',
            ],

            // Expense Categories - Transport
            [
                'name' => 'Transportasi',
                'type' => 'expense',
                'icon' => 'car',
                'color' => '#0ea5e9',
            ],
            [
                'name' => 'Bensin',
                'type' => 'expense',
                'icon' => 'gas-pump',
                'color' => '#06b6d4',
            ],
            [
                'name' => 'Taksi & Ojek',
                'type' => 'expense',
                'icon' => 'taxi',
                'color' => '#14b8a6',
            ],

            // Expense Categories - Shopping
            [
                'name' => 'Belanja Barang',
                'type' => 'expense',
                'icon' => 'shopping-bag',
                'color' => '#8b5cf6',
            ],
            [
                'name' => 'Pakaian & Fashion',
                'type' => 'expense',
                'icon' => 'shirt',
                'color' => '#d946ef',
            ],
            [
                'name' => 'Elektronik',
                'type' => 'expense',
                'icon' => 'laptop',
                'color' => '#6366f1',
            ],

            // Expense Categories - Utilities
            [
                'name' => 'Listrik & Air',
                'type' => 'expense',
                'icon' => 'bolt',
                'color' => '#eab308',
            ],
            [
                'name' => 'Internet & Telepon',
                'type' => 'expense',
                'icon' => 'wifi',
                'color' => '#14b8a6',
            ],
            [
                'name' => 'Sewa Rumah',
                'type' => 'expense',
                'icon' => 'home',
                'color' => '#f43f5e',
            ],

            // Expense Categories - Entertainment
            [
                'name' => 'Hiburan & Film',
                'type' => 'expense',
                'icon' => 'film',
                'color' => '#06b6d4',
            ],
            [
                'name' => 'Gaming',
                'type' => 'expense',
                'icon' => 'gamepad',
                'color' => '#8b5cf6',
            ],
            [
                'name' => 'Buku & Musik',
                'type' => 'expense',
                'icon' => 'book',
                'color' => '#f59e0b',
            ],

            // Expense Categories - Health & Fitness
            [
                'name' => 'Kesehatan',
                'type' => 'expense',
                'icon' => 'heartbeat',
                'color' => '#ef4444',
            ],
            [
                'name' => 'Gym & Olahraga',
                'type' => 'expense',
                'icon' => 'dumbbell',
                'color' => '#06b6d4',
            ],
            [
                'name' => 'Obat & Vitamin',
                'type' => 'expense',
                'icon' => 'pills',
                'color' => '#10b981',
            ],

            // Expense Categories - Education
            [
                'name' => 'Pendidikan',
                'type' => 'expense',
                'icon' => 'graduation-cap',
                'color' => '#3b82f6',
            ],
            [
                'name' => 'Kursus & Workshop',
                'type' => 'expense',
                'icon' => 'chalkboard-user',
                'color' => '#6366f1',
            ],

            // Expense Categories - Insurance & Finance
            [
                'name' => 'Asuransi',
                'type' => 'expense',
                'icon' => 'shield',
                'color' => '#8b5cf6',
            ],
            [
                'name' => 'Cicilan',
                'type' => 'expense',
                'icon' => 'credit-card',
                'color' => '#f43f5e',
            ],

            // Expense Categories - Gifts & Charity
            [
                'name' => 'Hadiah & Ucapan',
                'type' => 'expense',
                'icon' => 'gift',
                'color' => '#f43f5e',
            ],
            [
                'name' => 'Donasi & Zakat',
                'type' => 'expense',
                'icon' => 'hand-holding-heart',
                'color' => '#10b981',
            ],

            // Expense Categories - Savings & Miscellaneous
            [
                'name' => 'Tabungan',
                'type' => 'expense',
                'icon' => 'piggy-bank',
                'color' => '#22c55e',
            ],
            [
                'name' => 'Lain-lain',
                'type' => 'expense',
                'icon' => 'ellipsis',
                'color' => '#6b7280',
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
