<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Transaction;
use App\Models\User;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    use WithoutModelEvents;

    private $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create('id_ID');
    }

    public function run(): void
    {
        // Only seed transactions for non-auditor users
        $users = User::where('role', '!=', 'auditor')->get();

        if ($users->isEmpty()) {
            return;
        }

        $transactionTitles = [
            // Expense
            'Makan di Restoran',
            'Belanja Groceries',
            'Bensin',
            'Tagihan Internet',
            'Bulan Berlangganan',
            'Parkir',
            'Taksi',
            'Kopi Pagi',
            'Makan Siang',
            'Makan Malam',
            'Belanja Online',
            'Tagihan Listrik',
            'Biaya Admin',
            'Cicilan',
            'Hadiah Ulang Tahun',
            'Hiburan Bioskop',
            'Beli Buku',
            'Servis Motor',
            'Obat-obatan',
            'Potong Rambut',
            // Income
            'Gaji Bulan',
            'Bonus Kinerja',
            'Freelance Project',
            'Penjualan Barang',
            'Angpao',
            'Refund',
            'Komisi Penjualan',
            'Bayaran Overtime',
            'Tunjangan',
        ];

        $tagNames = [
            'Urgent',
            'Recurring',
            'Shopping',
            'Food',
            'Transport',
            'Utilities',
            'Entertainment',
            'Health',
            'Personal',
            'Business',
            'Work',
            'Home',
            'Investment',
        ];

        // Create tags for each user
        foreach ($users as $user) {
            foreach ($tagNames as $tagName) {
                Tag::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'name' => $tagName,
                    ],
                    [
                        'color' => $this->getRandomColor(),
                    ]
                );
            }
        }

        // Create transactions for each user
        foreach ($users as $user) {
            $userAccounts = $user->accounts;

            // Get categories accessible by this user (global + user-owned)
            $userCategories = Category::where(function ($query) use ($user) {
                $query->whereNull('user_id')
                      ->orWhere('user_id', $user->id);
            })->get();

            $userTags = $user->tags;

            if ($userAccounts->isEmpty() || $userCategories->isEmpty()) {
                continue;
            }

            for ($i = 0; $i < 20; $i++) {
                $category = $userCategories->random();
                $type = $category->type ?? 'expense';
                $amount = rand(15000, 500000);

                $transaction = Transaction::create([
                    'user_id' => $user->id,
                    'account_id' => $userAccounts->random()->id,
                    'category_id' => $category->id,
                    'type' => $type,
                    'amount' => $amount,
                    'title' => $transactionTitles[array_rand($transactionTitles)],
                    'description' => $this->faker->optional(0.5)->sentence(),
                    'transaction_date' => $this->faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
                ]);

                // Attach random tags
                if ($userTags->isNotEmpty()) {
                    $randomTags = $userTags->random(rand(0, min(3, $userTags->count())));
                    $transaction->tags()->attach($randomTags->pluck('id')->toArray());
                }
            }
        }
    }

    private function getRandomColor(): string
    {
        $colors = [
            '#FF6B6B',
            '#4ECDC4',
            '#45B7D1',
            '#FFA07A',
            '#98D8C8',
            '#F7DC6F',
            '#BB8FCE',
            '#85C1E2',
            '#F8B88B',
            '#52BE80',
            '#EC7063',
            '#5DADE2',
        ];

        return $colors[array_rand($colors)];
    }
}
