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

            // Get user's budgets & savings goals
            $userBudgets = \App\Models\Budgeting::where('user_id', $user->id)->get()->keyBy('name');
            $userGoals = \App\Models\SavingsGoals::where('user_id', $user->id)->get()->keyBy('name');

            // =========================================================
            // TRANSAKSI EXPENSE — untuk mengisi budget
            // =========================================================

            // Budget Makan (limit 400rb) → LEBIHI: buat transaksi total ~450rb
            $makanCategories = ['Makan & Minuman', 'Kopi & Snack', 'Restoran'];
            $makanTotal = 0;
            $makanBudgetId = $userBudgets->get('Budget Makan')?->id;
            while ($makanTotal < 450000) {
                $amount = rand(25000, 80000);
                $makanTotal += $amount;
                $catName = $makanCategories[array_rand($makanCategories)];
                $cat = $userCategories->firstWhere('name', $catName);
                $transaction = Transaction::create([
                    'user_id' => $user->id,
                    'account_id' => $userAccounts->random()->id,
                    'category_id' => $cat?->id ?? $userCategories->first()?->id,
                    'budgeting_id' => $makanBudgetId,
                    'type' => 'expense',
                    'amount' => $amount,
                    'title' => $transactionTitles[array_rand(array_slice($transactionTitles, 0, 20))],
                    'description' => $this->faker->optional(0.3)->sentence(),
                    'transaction_date' => $this->faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
                ]);
                if ($userTags->isNotEmpty()) {
                    $transaction->tags()->attach($userTags->random(rand(1, min(2, $userTags->count())))->pluck('id')->toArray());
                }
            }

            // Budget Transport (limit 500rb) → SETENGAH: buat transaksi total ~250rb
            $transportCategories = ['Transportasi', 'Bensin', 'Taksi & Ojek'];
            $transportTotal = 0;
            $transportBudgetId = $userBudgets->get('Budget Transport')?->id;
            while ($transportTotal < 250000) {
                $amount = rand(15000, 50000);
                $transportTotal += $amount;
                $catName = $transportCategories[array_rand($transportCategories)];
                $cat = $userCategories->firstWhere('name', $catName);
                $transaction = Transaction::create([
                    'user_id' => $user->id,
                    'account_id' => $userAccounts->random()->id,
                    'category_id' => $cat?->id ?? $userCategories->first()?->id,
                    'budgeting_id' => $transportBudgetId,
                    'type' => 'expense',
                    'amount' => $amount,
                    'title' => $transactionTitles[array_rand(array_slice($transactionTitles, 0, 20))],
                    'description' => $this->faker->optional(0.3)->sentence(),
                    'transaction_date' => $this->faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
                ]);
                if ($userTags->isNotEmpty()) {
                    $transaction->tags()->attach($userTags->random(rand(1, min(2, $userTags->count())))->pluck('id')->toArray());
                }
            }

            // Budget Belanja (limit 600rb) → LEBIHI: buat transaksi total ~700rb
            $belanjaCategories = ['Belanja Barang', 'Pakaian & Fashion', 'Elektronik'];
            $belanjaTotal = 0;
            $belanjaBudgetId = $userBudgets->get('Budget Belanja')?->id;
            while ($belanjaTotal < 700000) {
                $amount = rand(50000, 150000);
                $belanjaTotal += $amount;
                $catName = $belanjaCategories[array_rand($belanjaCategories)];
                $cat = $userCategories->firstWhere('name', $catName);
                $transaction = Transaction::create([
                    'user_id' => $user->id,
                    'account_id' => $userAccounts->random()->id,
                    'category_id' => $cat?->id ?? $userCategories->first()?->id,
                    'budgeting_id' => $belanjaBudgetId,
                    'type' => 'expense',
                    'amount' => $amount,
                    'title' => $transactionTitles[array_rand(array_slice($transactionTitles, 0, 20))],
                    'description' => $this->faker->optional(0.3)->sentence(),
                    'transaction_date' => $this->faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
                ]);
                if ($userTags->isNotEmpty()) {
                    $transaction->tags()->attach($userTags->random(rand(1, min(2, $userTags->count())))->pluck('id')->toArray());
                }
            }

            // Budget Internet (limit 250rb) → SEDIKIT: buat transaksi total ~100rb
            $internetBudgetId = $userBudgets->get('Budget Internet')?->id;
            $internetCat = $userCategories->firstWhere('name', 'Internet & Telepon');
            for ($i = 0; $i < 2; $i++) {
                $amount = rand(40000, 60000);
                $transaction = Transaction::create([
                    'user_id' => $user->id,
                    'account_id' => $userAccounts->random()->id,
                    'category_id' => $internetCat?->id ?? $userCategories->first()?->id,
                    'budgeting_id' => $internetBudgetId,
                    'type' => 'expense',
                    'amount' => $amount,
                    'title' => 'Tagihan Internet',
                    'description' => $this->faker->optional(0.3)->sentence(),
                    'transaction_date' => $this->faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
                ]);
                if ($userTags->isNotEmpty()) {
                    $transaction->tags()->attach($userTags->random(rand(1, min(2, $userTags->count())))->pluck('id')->toArray());
                }
            }

            // Budget Hiburan — TIDAK dibuatkan transaksi apapun (abu-abu/kosong)

            // =========================================================
            // TRANSAKSI INCOME — untuk mengisi savings goals
            // =========================================================

            // Goal: Liburan Akhir Tahun (completed, 2jt/2jt) — sudah completed via seeder
            // Goal: Beli Sepatu Baru (current 340rb, target 750rb) — tambah income ~200rb
            $goalSepatu = $userGoals->get('Beli Sepatu Baru');
            if ($goalSepatu) {
                $incomeAmount = 200000;
                $salaryCat = $userCategories->firstWhere('name', 'Salary') ?? $userCategories->firstWhere('type', 'income');
                $transaction = Transaction::create([
                    'user_id' => $user->id,
                    'account_id' => $userAccounts->random()->id,
                    'category_id' => $salaryCat?->id ?? $userCategories->first()?->id,
                    'savings_goal_id' => $goalSepatu->id,
                    'type' => 'income',
                    'amount' => $incomeAmount,
                    'title' => 'Freelance Project',
                    'description' => 'Pemasukan untuk goal sepatu',
                    'transaction_date' => $this->faker->dateTimeBetween('-15 days', 'now')->format('Y-m-d'),
                ]);
                $goalSepatu->increment('current_amount', $incomeAmount);
            }

            // Goal: Beli Laptop Gaming (current 0, target 15jt) — jangan dikasih income (tetap 0/kosong)

            // =========================================================
            // SISA TRANSAKSI RANDOM — untuk melengkapi (supaya ada variasi)
            // =========================================================
            $additionalCount = 10;
            // Kumpulin kategori expense selain yang udah dipake budget
            $usedCategoryNames = array_merge($makanCategories, $transportCategories, $belanjaCategories, ['Internet & Telepon']);
            $otherExpenseCategories = $userCategories->where('type', 'expense')
                ->reject(fn($c) => in_array($c->name, $usedCategoryNames));
            $incomeCategories = $userCategories->where('type', 'income');

            for ($i = 0; $i < $additionalCount; $i++) {
                $isExpense = $i < 7;
                if ($isExpense && $otherExpenseCategories->isNotEmpty()) {
                    $category = $otherExpenseCategories->random();
                    $type = 'expense';
                } else {
                    $category = $incomeCategories->isNotEmpty() ? $incomeCategories->random() : $userCategories->first();
                    $type = 'income';
                }
                $amount = $type === 'expense' ? rand(15000, 200000) : rand(100000, 3000000);

                $transaction = Transaction::create([
                    'user_id' => $user->id,
                    'account_id' => $userAccounts->random()->id,
                    'category_id' => $category->id,
                    'budgeting_id' => null,
                    'savings_goal_id' => null,
                    'type' => $type,
                    'amount' => $amount,
                    'title' => $transactionTitles[array_rand($transactionTitles)],
                    'description' => $this->faker->optional(0.5)->sentence(),
                    'transaction_date' => $this->faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
                ]);

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