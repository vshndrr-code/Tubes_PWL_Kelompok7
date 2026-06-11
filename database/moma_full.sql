-- ============================================================
-- DATABASE: MOMA (Money Manager)
-- File ini menggabungkan seluruh struktur tabel (migration)
-- dan data awal (seeder) dalam satu file SQL
-- ============================================================

-- -----------------------------------------------------------
-- 1. TABEL USERS (termasuk kolom dari migration tambahan)
-- -----------------------------------------------------------
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL UNIQUE,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `email_verified_at` TIMESTAMP NULL,
    `onboarding_completed` TINYINT(1) NOT NULL DEFAULT 0,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('user', 'auditor') NOT NULL DEFAULT 'user',
    `currency` VARCHAR(10) NOT NULL DEFAULT 'IDR',
    `remember_token` VARCHAR(100) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- 2. TABEL ACCOUNTS
-- -----------------------------------------------------------
CREATE TABLE `accounts` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NULL,
    `name` VARCHAR(100) NOT NULL,
    `type` ENUM('cash', 'bank', 'credit', 'other') NOT NULL DEFAULT 'cash',
    `is_pinned` TINYINT(1) NOT NULL DEFAULT 0,
    `archived_at` TIMESTAMP NULL,
    `balance` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- 3. TABEL CATEGORIES
-- -----------------------------------------------------------
CREATE TABLE `categories` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NULL,
    `name` VARCHAR(100) NOT NULL,
    `type` ENUM('expense', 'income') NOT NULL DEFAULT 'expense',
    `icon` VARCHAR(50) NULL,
    `color` VARCHAR(7) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- 4. TABEL TRANSACTIONS
-- -----------------------------------------------------------
CREATE TABLE `transactions` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `account_id` BIGINT UNSIGNED NOT NULL,
    `category_id` BIGINT UNSIGNED NOT NULL,
    `budgeting_id` BIGINT UNSIGNED NULL,
    `savings_goal_id` BIGINT UNSIGNED NULL,
    `type` ENUM('income', 'expense') NOT NULL DEFAULT 'expense',
    `amount` DECIMAL(15, 2) NOT NULL,
    `title` VARCHAR(150) NOT NULL,
    `description` TEXT NULL,
    `transaction_date` DATE NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `transactions_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
    CONSTRAINT `transactions_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
    CONSTRAINT `transactions_budgeting_id_foreign` FOREIGN KEY (`budgeting_id`) REFERENCES `budgetings` (`id`) ON DELETE SET NULL,
    CONSTRAINT `transactions_savings_goal_id_foreign` FOREIGN KEY (`savings_goal_id`) REFERENCES `savings_goals` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- 5. TABEL TAGS
-- -----------------------------------------------------------
CREATE TABLE `tags` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `color` VARCHAR(7) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `tags_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- 6. TABEL TRANSACTION_TAGS (Pivot)
-- -----------------------------------------------------------
CREATE TABLE `transaction_tags` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `transaction_id` BIGINT UNSIGNED NOT NULL,
    `tag_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `transaction_tags_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
    CONSTRAINT `transaction_tags_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE,
    UNIQUE KEY `transaction_tags_transaction_id_tag_id_unique` (`transaction_id`, `tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- 7. TABEL BUDGETINGS
-- -----------------------------------------------------------
CREATE TABLE `budgetings` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `category_id` BIGINT UNSIGNED NULL,
    `limit_amount` DECIMAL(15, 2) NOT NULL,
    `month` TINYINT UNSIGNED NOT NULL,
    `year` SMALLINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `budgetings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `budgetings_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- 8. TABEL NOTIFICATIONS
-- -----------------------------------------------------------
CREATE TABLE `notifications` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(150) NOT NULL,
    `type` VARCHAR(50) NOT NULL,
    `message` TEXT NOT NULL,
    `is_read` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- 9. TABEL TRANSFERS
-- -----------------------------------------------------------
CREATE TABLE `transfers` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `from_account_id` BIGINT UNSIGNED NOT NULL,
    `to_account_id` BIGINT UNSIGNED NOT NULL,
    `amount` DECIMAL(15, 2) NOT NULL,
    `note` TEXT NULL,
    `transfer_date` DATETIME NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `transfers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `transfers_from_account_id_foreign` FOREIGN KEY (`from_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
    CONSTRAINT `transfers_to_account_id_foreign` FOREIGN KEY (`to_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- 10. TABEL RECURRING_TRANSACTIONS
-- -----------------------------------------------------------
CREATE TABLE `recurring_transactions` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `account_id` BIGINT UNSIGNED NOT NULL,
    `category_id` BIGINT UNSIGNED NOT NULL,
    `type` ENUM('income', 'expense') NOT NULL DEFAULT 'expense',
    `title` VARCHAR(150) NOT NULL,
    `description` TEXT NULL,
    `amount` DECIMAL(15, 2) NOT NULL,
    `frequency` ENUM('daily', 'weekly', 'monthly', 'yearly') NOT NULL DEFAULT 'monthly',
    `interval` TINYINT UNSIGNED NOT NULL DEFAULT 1,
    `start_date` DATE NOT NULL,
    `end_date` DATE NULL,
    `next_occurrence_date` DATE NULL,
    `active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `recurring_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `recurring_transactions_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
    CONSTRAINT `recurring_transactions_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- 11. TABEL RECURRING_TRANSACTION_TAG (Pivot)
-- -----------------------------------------------------------
CREATE TABLE `recurring_transaction_tag` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `recurring_transaction_id` BIGINT UNSIGNED NOT NULL,
    `tag_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `rtt_recurring_transaction_id_foreign` FOREIGN KEY (`recurring_transaction_id`) REFERENCES `recurring_transactions` (`id`) ON DELETE CASCADE,
    CONSTRAINT `rtt_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- 12. TABEL SAVINGS_GOALS
-- -----------------------------------------------------------
CREATE TABLE `savings_goals` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `account_id` BIGINT UNSIGNED NULL,
    `name` VARCHAR(255) NOT NULL,
    `target_amount` DECIMAL(15, 2) NOT NULL,
    `current_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    `deadline` DATE NULL,
    `status` ENUM('active', 'completed', 'cancelled') NOT NULL DEFAULT 'active',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `savings_goals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- DATA SEEDING
-- ============================================================

-- -----------------------------------------------------------
-- USERS (UserSeeder + AuditorSeeder)
-- -----------------------------------------------------------
INSERT INTO `users` (`name`, `email`, `email_verified_at`, `password`, `onboarding_completed`, `currency`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
('Vasha',  'vasha@moma.local',  NOW(), '$2y$12$hashed_password_placeholder_kelompok7', 1, 'IDR', 'user',    'token_placeholder', NOW(), NOW()),
('Dayuk',  'dayuk@moma.local',  NOW(), '$2y$12$hashed_password_placeholder_kelompok7', 1, 'IDR', 'user',    'token_placeholder', NOW(), NOW()),
('Jiyad',  'jiyad@moma.local',  NOW(), '$2y$12$hashed_password_placeholder_kelompok7', 1, 'IDR', 'user',    'token_placeholder', NOW(), NOW()),
('Azka',   'azka@moma.local',   NOW(), '$2y$12$hashed_password_placeholder_kelompok7', 1, 'IDR', 'user',    'token_placeholder', NOW(), NOW()),
('Fira',   'fira@moma.local',   NOW(), '$2y$12$hashed_password_placeholder_kelompok7', 1, 'IDR', 'user',    'token_placeholder', NOW(), NOW()),
('SDGs Auditor', 'auditor@moma.com', NOW(), '$2y$12$hashed_password_placeholder_password', 1, 'IDR', 'auditor', 'token_placeholder', NOW(), NOW());

-- -----------------------------------------------------------
-- CATEGORIES: INCOME (dari migration insert_income_categories)
-- -----------------------------------------------------------
INSERT INTO `categories` (`user_id`, `name`, `type`, `icon`, `color`, `created_at`, `updated_at`) VALUES
(NULL, 'Salary',            'income', 'money-bill-wave', '#22c55e', NOW(), NOW()),
(NULL, 'Other Income',      'income', 'gift',            '#f59e0b', NOW(), NOW()),
(NULL, 'Incoming transfer', 'income', 'arrow-right',     '#3b82f6', NOW(), NOW()),
(NULL, 'Collect Interest',  'income', 'percent',         '#0f766e', NOW(), NOW());

-- -----------------------------------------------------------
-- CATEGORIES: EXPENSE (dari migration insert_expense_categories)
-- -----------------------------------------------------------
INSERT INTO `categories` (`user_id`, `name`, `type`, `icon`, `color`, `created_at`, `updated_at`) VALUES
(NULL, 'Makan & Minuman',   'expense', 'utensils',          '#f97316', NOW(), NOW()),
(NULL, 'Kopi & Snack',      'expense', 'coffee',            '#d97706', NOW(), NOW()),
(NULL, 'Restoran',           'expense', 'drumstick-bite',    '#ea580c', NOW(), NOW()),
(NULL, 'Transportasi',       'expense', 'car',               '#0ea5e9', NOW(), NOW()),
(NULL, 'Bensin',             'expense', 'gas-pump',          '#06b6d4', NOW(), NOW()),
(NULL, 'Taksi & Ojek',       'expense', 'taxi',              '#14b8a6', NOW(), NOW()),
(NULL, 'Belanja Barang',     'expense', 'shopping-bag',      '#8b5cf6', NOW(), NOW()),
(NULL, 'Pakaian & Fashion',  'expense', 'shirt',             '#d946ef', NOW(), NOW()),
(NULL, 'Elektronik',         'expense', 'laptop',            '#6366f1', NOW(), NOW()),
(NULL, 'Listrik & Air',      'expense', 'bolt',              '#eab308', NOW(), NOW()),
(NULL, 'Internet & Telepon', 'expense', 'wifi',              '#14b8a6', NOW(), NOW()),
(NULL, 'Sewa Rumah',         'expense', 'home',              '#f43f5e', NOW(), NOW()),
(NULL, 'Hiburan & Film',     'expense', 'film',              '#06b6d4', NOW(), NOW()),
(NULL, 'Gaming',             'expense', 'gamepad',           '#8b5cf6', NOW(), NOW()),
(NULL, 'Buku & Musik',       'expense', 'book',              '#f59e0b', NOW(), NOW()),
(NULL, 'Kesehatan',          'expense', 'heartbeat',         '#ef4444', NOW(), NOW()),
(NULL, 'Gym & Olahraga',     'expense', 'dumbbell',          '#06b6d4', NOW(), NOW()),
(NULL, 'Obat & Vitamin',     'expense', 'pills',             '#10b981', NOW(), NOW()),
(NULL, 'Pendidikan',         'expense', 'graduation-cap',    '#3b82f6', NOW(), NOW()),
(NULL, 'Kursus & Workshop',  'expense', 'chalkboard-user',   '#6366f1', NOW(), NOW()),
(NULL, 'Asuransi',           'expense', 'shield',            '#8b5cf6', NOW(), NOW()),
(NULL, 'Cicilan',            'expense', 'credit-card',       '#f43f5e', NOW(), NOW()),
(NULL, 'Hadiah & Ucapan',    'expense', 'gift',              '#f43f5e', NOW(), NOW()),
(NULL, 'Donasi & Zakat',     'expense', 'hand-holding-heart','#10b981', NOW(), NOW()),
(NULL, 'Tabungan',           'expense', 'piggy-bank',        '#22c55e', NOW(), NOW()),
(NULL, 'Lain-lain',          'expense', 'ellipsis',          '#6b7280', NOW(), NOW());

-- -----------------------------------------------------------
-- ACCOUNTS (AccountSeeder - untuk 5 user non-auditor)
-- -----------------------------------------------------------
-- Asumsikan ID user: Vasha=1, Dayuk=2, Jiyad=3, Azka=4, Fira=5
INSERT INTO `accounts` (`user_id`, `name`, `type`, `balance`, `created_at`, `updated_at`) VALUES
-- Vasha
(1, 'Bank BCA',          'bank',   1250000.00, NOW(), NOW()),
(1, 'Cash',              'cash',   250000.00,  NOW(), NOW()),
(1, 'Bank Mandiri',      'bank',   860000.00,  NOW(), NOW()),
(1, 'OVO',               'other',  520000.00,  NOW(), NOW()),
(1, 'DANA',              'other',  310000.00,  NOW(), NOW()),
(1, 'Kartu Kredit BNI',  'credit', 0.00,       NOW(), NOW()),
-- Dayuk
(2, 'Bank BCA',          'bank',   1250000.00, NOW(), NOW()),
(2, 'Cash',              'cash',   250000.00,  NOW(), NOW()),
(2, 'Bank Mandiri',      'bank',   860000.00,  NOW(), NOW()),
(2, 'OVO',               'other',  520000.00,  NOW(), NOW()),
(2, 'DANA',              'other',  310000.00,  NOW(), NOW()),
(2, 'Kartu Kredit BNI',  'credit', 0.00,       NOW(), NOW()),
-- Jiyad
(3, 'Bank BCA',          'bank',   1250000.00, NOW(), NOW()),
(3, 'Cash',              'cash',   250000.00,  NOW(), NOW()),
(3, 'Bank Mandiri',      'bank',   860000.00,  NOW(), NOW()),
(3, 'OVO',               'other',  520000.00,  NOW(), NOW()),
(3, 'DANA',              'other',  310000.00,  NOW(), NOW()),
(3, 'Kartu Kredit BNI',  'credit', 0.00,       NOW(), NOW()),
-- Azka
(4, 'Bank BCA',          'bank',   1250000.00, NOW(), NOW()),
(4, 'Cash',              'cash',   250000.00,  NOW(), NOW()),
(4, 'Bank Mandiri',      'bank',   860000.00,  NOW(), NOW()),
(4, 'OVO',               'other',  520000.00,  NOW(), NOW()),
(4, 'DANA',              'other',  310000.00,  NOW(), NOW()),
(4, 'Kartu Kredit BNI',  'credit', 0.00,       NOW(), NOW()),
-- Fira
(5, 'Bank BCA',          'bank',   1250000.00, NOW(), NOW()),
(5, 'Cash',              'cash',   250000.00,  NOW(), NOW()),
(5, 'Bank Mandiri',      'bank',   860000.00,  NOW(), NOW()),
(5, 'OVO',               'other',  520000.00,  NOW(), NOW()),
(5, 'DANA',              'other',  310000.00,  NOW(), NOW()),
(5, 'Kartu Kredit BNI',  'credit', 0.00,       NOW(), NOW());

-- -----------------------------------------------------------
-- BUDGETS (BudgetSeeder - untuk 5 user non-auditor)
-- Asumsikan kategori: Makan & Minuman=5, Transportasi=8, Belanja Barang=11, Internet & Telepon=15
-- -----------------------------------------------------------
SET @month = MONTH(NOW());
SET @year = YEAR(NOW());

INSERT INTO `budgetings` (`user_id`, `name`, `category_id`, `limit_amount`, `month`, `year`, `created_at`, `updated_at`) VALUES
-- Vasha
(1, 'Budget Makan',    5,  500000.00, @month, @year, NOW(), NOW()),
(1, 'Budget Transport', 8,  400000.00, @month, @year, NOW(), NOW()),
(1, 'Budget Belanja',  11, 600000.00, @month, @year, NOW(), NOW()),
(1, 'Budget Internet', 15, 250000.00, @month, @year, NOW(), NOW()),
-- Dayuk
(2, 'Budget Makan',    5,  500000.00, @month, @year, NOW(), NOW()),
(2, 'Budget Transport', 8,  400000.00, @month, @year, NOW(), NOW()),
(2, 'Budget Belanja',  11, 600000.00, @month, @year, NOW(), NOW()),
(2, 'Budget Internet', 15, 250000.00, @month, @year, NOW(), NOW()),
-- Jiyad
(3, 'Budget Makan',    5,  500000.00, @month, @year, NOW(), NOW()),
(3, 'Budget Transport', 8,  400000.00, @month, @year, NOW(), NOW()),
(3, 'Budget Belanja',  11, 600000.00, @month, @year, NOW(), NOW()),
(3, 'Budget Internet', 15, 250000.00, @month, @year, NOW(), NOW()),
-- Azka
(4, 'Budget Makan',    5,  500000.00, @month, @year, NOW(), NOW()),
(4, 'Budget Transport', 8,  400000.00, @month, @year, NOW(), NOW()),
(4, 'Budget Belanja',  11, 600000.00, @month, @year, NOW(), NOW()),
(4, 'Budget Internet', 15, 250000.00, @month, @year, NOW(), NOW()),
-- Fira
(5, 'Budget Makan',    5,  500000.00, @month, @year, NOW(), NOW()),
(5, 'Budget Transport', 8,  400000.00, @month, @year, NOW(), NOW()),
(5, 'Budget Belanja',  11, 600000.00, @month, @year, NOW(), NOW()),
(5, 'Budget Internet', 15, 250000.00, @month, @year, NOW(), NOW());

-- -----------------------------------------------------------
-- SAVINGS GOALS (GoalSeeder - untuk 5 user non-auditor)
-- -----------------------------------------------------------
INSERT INTO `savings_goals` (`user_id`, `name`, `target_amount`, `current_amount`, `deadline`, `status`, `created_at`, `updated_at`) VALUES
-- Vasha
(1, 'Beli Sepatu', 750000.00,  150000.00,  DATE_ADD(NOW(), INTERVAL 6 WEEK),  'active', NOW(), NOW()),
(1, 'Beli Laptop', 5500000.00, 950000.00,  DATE_ADD(NOW(), INTERVAL 3 MONTH), 'active', NOW(), NOW()),
-- Dayuk
(2, 'Beli Sepatu', 750000.00,  150000.00,  DATE_ADD(NOW(), INTERVAL 6 WEEK),  'active', NOW(), NOW()),
(2, 'Beli Laptop', 5500000.00, 950000.00,  DATE_ADD(NOW(), INTERVAL 3 MONTH), 'active', NOW(), NOW()),
-- Jiyad
(3, 'Beli Sepatu', 750000.00,  150000.00,  DATE_ADD(NOW(), INTERVAL 6 WEEK),  'active', NOW(), NOW()),
(3, 'Beli Laptop', 5500000.00, 950000.00,  DATE_ADD(NOW(), INTERVAL 3 MONTH), 'active', NOW(), NOW()),
-- Azka
(4, 'Beli Sepatu', 750000.00,  150000.00,  DATE_ADD(NOW(), INTERVAL 6 WEEK),  'active', NOW(), NOW()),
(4, 'Beli Laptop', 5500000.00, 950000.00,  DATE_ADD(NOW(), INTERVAL 3 MONTH), 'active', NOW(), NOW()),
-- Fira
(5, 'Beli Sepatu', 750000.00,  150000.00,  DATE_ADD(NOW(), INTERVAL 6 WEEK),  'active', NOW(), NOW()),
(5, 'Beli Laptop', 5500000.00, 950000.00,  DATE_ADD(NOW(), INTERVAL 3 MONTH), 'active', NOW(), NOW());

-- -----------------------------------------------------------
-- TAGS (dari TransactionSeeder - untuk 5 user non-auditor)
-- -----------------------------------------------------------
INSERT INTO `tags` (`user_id`, `name`, `color`, `created_at`, `updated_at`) VALUES
-- Vasha
(1, 'Urgent',        '#FF6B6B', NOW(), NOW()),
(1, 'Recurring',     '#4ECDC4', NOW(), NOW()),
(1, 'Shopping',      '#45B7D1', NOW(), NOW()),
(1, 'Food',          '#FFA07A', NOW(), NOW()),
(1, 'Transport',     '#98D8C8', NOW(), NOW()),
(1, 'Utilities',     '#F7DC6F', NOW(), NOW()),
(1, 'Entertainment', '#BB8FCE', NOW(), NOW()),
(1, 'Health',        '#85C1E2', NOW(), NOW()),
(1, 'Personal',      '#F8B88B', NOW(), NOW()),
(1, 'Business',      '#52BE80', NOW(), NOW()),
(1, 'Work',          '#EC7063', NOW(), NOW()),
(1, 'Home',          '#5DADE2', NOW(), NOW()),
(1, 'Investment',    '#FF6B6B', NOW(), NOW()),
-- Dayuk
(2, 'Urgent',        '#FF6B6B', NOW(), NOW()),
(2, 'Recurring',     '#4ECDC4', NOW(), NOW()),
(2, 'Shopping',      '#45B7D1', NOW(), NOW()),
(2, 'Food',          '#FFA07A', NOW(), NOW()),
(2, 'Transport',     '#98D8C8', NOW(), NOW()),
(2, 'Utilities',     '#F7DC6F', NOW(), NOW()),
(2, 'Entertainment', '#BB8FCE', NOW(), NOW()),
(2, 'Health',        '#85C1E2', NOW(), NOW()),
(2, 'Personal',      '#F8B88B', NOW(), NOW()),
(2, 'Business',      '#52BE80', NOW(), NOW()),
(2, 'Work',          '#EC7063', NOW(), NOW()),
(2, 'Home',          '#5DADE2', NOW(), NOW()),
(2, 'Investment',    '#FF6B6B', NOW(), NOW()),
-- Jiyad
(3, 'Urgent',        '#FF6B6B', NOW(), NOW()),
(3, 'Recurring',     '#4ECDC4', NOW(), NOW()),
(3, 'Shopping',      '#45B7D1', NOW(), NOW()),
(3, 'Food',          '#FFA07A', NOW(), NOW()),
(3, 'Transport',     '#98D8C8', NOW(), NOW()),
(3, 'Utilities',     '#F7DC6F', NOW(), NOW()),
(3, 'Entertainment', '#BB8FCE', NOW(), NOW()),
(3, 'Health',        '#85C1E2', NOW(), NOW()),
(3, 'Personal',      '#F8B88B', NOW(), NOW()),
(3, 'Business',      '#52BE80', NOW(), NOW()),
(3, 'Work',          '#EC7063', NOW(), NOW()),
(3, 'Home',          '#5DADE2', NOW(), NOW()),
(3, 'Investment',    '#FF6B6B', NOW(), NOW()),
-- Azka
(4, 'Urgent',        '#FF6B6B', NOW(), NOW()),
(4, 'Recurring',     '#4ECDC4', NOW(), NOW()),
(4, 'Shopping',      '#45B7D1', NOW(), NOW()),
(4, 'Food',          '#FFA07A', NOW(), NOW()),
(4, 'Transport',     '#98D8C8', NOW(), NOW()),
(4, 'Utilities',     '#F7DC6F', NOW(), NOW()),
(4, 'Entertainment', '#BB8FCE', NOW(), NOW()),
(4, 'Health',        '#85C1E2', NOW(), NOW()),
(4, 'Personal',      '#F8B88B', NOW(), NOW()),
(4, 'Business',      '#52BE80', NOW(), NOW()),
(4, 'Work',          '#EC7063', NOW(), NOW()),
(4, 'Home',          '#5DADE2', NOW(), NOW()),
(4, 'Investment',    '#FF6B6B', NOW(), NOW()),
-- Fira
(5, 'Urgent',        '#FF6B6B', NOW(), NOW()),
(5, 'Recurring',     '#4ECDC4', NOW(), NOW()),
(5, 'Shopping',      '#45B7D1', NOW(), NOW()),
(5, 'Food',          '#FFA07A', NOW(), NOW()),
(5, 'Transport',     '#98D8C8', NOW(), NOW()),
(5, 'Utilities',     '#F7DC6F', NOW(), NOW()),
(5, 'Entertainment', '#BB8FCE', NOW(), NOW()),
(5, 'Health',        '#85C1E2', NOW(), NOW()),
(5, 'Personal',      '#F8B88B', NOW(), NOW()),
(5, 'Business',      '#52BE80', NOW(), NOW()),
(5, 'Work',          '#EC7063', NOW(), NOW()),
(5, 'Home',          '#5DADE2', NOW(), NOW()),
(5, 'Investment',    '#FF6B6B', NOW(), NOW());

-- -----------------------------------------------------------
-- TRANSACTIONS (TransactionSeeder - 20 transaksi per user)
-- Catatan: Karena TransactionSeeder asli menggunakan Faker (random),
-- di sini dibuat 20 transaksi sample per user (total 100 transaksi)
-- -----------------------------------------------------------
-- === VASHA (user_id=1) ===
INSERT INTO `transactions` (`user_id`, `account_id`, `category_id`, `type`, `amount`, `title`, `description`, `transaction_date`, `created_at`, `updated_at`) VALUES
(1, 1, 5,  'expense', 45000.00,  'Makan di Restoran',    'Makan siang bersama teman',       DATE_SUB(NOW(), INTERVAL 1 DAY),  NOW(), NOW()),
(1, 2, 8,  'expense', 30000.00,  'Bensin',               NULL,                               DATE_SUB(NOW(), INTERVAL 2 DAY),  NOW(), NOW()),
(1, 3, 11, 'expense', 150000.00, 'Belanja Online',        'Beli kebutuhan rumah tangga',     DATE_SUB(NOW(), INTERVAL 3 DAY),  NOW(), NOW()),
(1, 4, 5,  'expense', 25000.00,  'Kopi Pagi',            'Kopi di Starbucks',               DATE_SUB(NOW(), INTERVAL 4 DAY),  NOW(), NOW()),
(1, 5, 8,  'expense', 20000.00,  'Parkir',               NULL,                               DATE_SUB(NOW(), INTERVAL 5 DAY),  NOW(), NOW()),
(1, 1, 15, 'expense', 200000.00, 'Tagihan Internet',     'Pembayaran IndiHome bulan ini',   DATE_SUB(NOW(), INTERVAL 6 DAY),  NOW(), NOW()),
(1, 2, 18, 'expense', 75000.00,  'Obat-obatan',          'Beli obat di apotek',             DATE_SUB(NOW(), INTERVAL 7 DAY),  NOW(), NOW()),
(1, 3, 14, 'expense', 50000.00,  'Hiburan Bioskop',      'Nonton film bersama',             DATE_SUB(NOW(), INTERVAL 8 DAY),  NOW(), NOW()),
(1, 4, 11, 'expense', 120000.00, 'Belanja Groceries',    NULL,                               DATE_SUB(NOW(), INTERVAL 9 DAY),  NOW(), NOW()),
(1, 5, 5,  'expense', 35000.00,  'Makan Siang',          'Makan di warteg',                 DATE_SUB(NOW(), INTERVAL 10 DAY), NOW(), NOW()),
(1, 1, 1,  'income',  4500000.00,'Gaji Bulan',           'Gaji bulan ini',                  DATE_SUB(NOW(), INTERVAL 14 DAY), NOW(), NOW()),
(1, 2, 2,  'income',  500000.00, 'Freelance Project',    'Project desain website',          DATE_SUB(NOW(), INTERVAL 15 DAY), NOW(), NOW()),
(1, 3, 2,  'income',  200000.00, 'Penjualan Barang',     'Jual buku bekas',                 DATE_SUB(NOW(), INTERVAL 16 DAY), NOW(), NOW()),
(1, 4, 3,  'income',  150000.00, 'Refund',               'Refund belanja online',           DATE_SUB(NOW(), INTERVAL 17 DAY), NOW(), NOW()),
(1, 5, 1,  'income',  1000000.00,'Bonus Kinerja',        'Bonus akhir bulan',               DATE_SUB(NOW(), INTERVAL 18 DAY), NOW(), NOW()),
(1, 1, 7,  'expense', 100000.00, 'Beli Buku',            'Buku novel',                      DATE_SUB(NOW(), INTERVAL 19 DAY), NOW(), NOW()),
(1, 2, 17, 'expense', 50000.00,  'Potong Rambut',        NULL,                               DATE_SUB(NOW(), INTERVAL 20 DAY), NOW(), NOW()),
(1, 3, 22, 'expense', 300000.00, 'Cicilan',              'Cicilan motor bulan ini',         DATE_SUB(NOW(), INTERVAL 21 DAY), NOW(), NOW()),
(1, 4, 16, 'expense', 35000.00,  'Servis Motor',         'Ganti oli',                       DATE_SUB(NOW(), INTERVAL 22 DAY), NOW(), NOW()),
(1, 5, 2,  'income',  250000.00, 'Tunjangan',            'Tunjangan transportasi',          DATE_SUB(NOW(), INTERVAL 23 DAY), NOW(), NOW());

-- === DAYUK (user_id=2) ===
INSERT INTO `transactions` (`user_id`, `account_id`, `category_id`, `type`, `amount`, `title`, `description`, `transaction_date`, `created_at`, `updated_at`) VALUES
(2, 7,  5,  'expense', 35000.00,  'Makan di Restoran',    NULL,                               DATE_SUB(NOW(), INTERVAL 1 DAY),  NOW(), NOW()),
(2, 8,  8,  'expense', 25000.00,  'Bensin',               'Isi bensin motor',                 DATE_SUB(NOW(), INTERVAL 2 DAY),  NOW(), NOW()),
(2, 9,  11, 'expense', 200000.00, 'Belanja Online',       'Beli baju',                        DATE_SUB(NOW(), INTERVAL 3 DAY),  NOW(), NOW()),
(2, 10, 5,  'expense', 20000.00,  'Kopi Pagi',            NULL,                               DATE_SUB(NOW(), INTERVAL 4 DAY),  NOW(), NOW()),
(2, 11, 8,  'expense', 15000.00,  'Taksi',                'Perjalanan ke kampus',             DATE_SUB(NOW(), INTERVAL 5 DAY),  NOW(), NOW()),
(2, 7,  15, 'expense', 150000.00, 'Tagihan Internet',     'Bayar wifi',                       DATE_SUB(NOW(), INTERVAL 6 DAY),  NOW(), NOW()),
(2, 8,  6,  'expense', 25000.00,  'Makan Siang',          NULL,                               DATE_SUB(NOW(), INTERVAL 7 DAY),  NOW(), NOW()),
(2, 9,  14, 'expense', 45000.00,  'Hiburan Bioskop',      'Nonton film',                      DATE_SUB(NOW(), INTERVAL 8 DAY),  NOW(), NOW()),
(2, 10, 5,  'expense', 50000.00,  'Makan Malam',          'Makan di restoran',                DATE_SUB(NOW(), INTERVAL 9 DAY),  NOW(), NOW()),
(2, 11, 11, 'expense', 85000.00,  'Belanja Groceries',    'Beli bahan makanan',               DATE_SUB(NOW(), INTERVAL 10 DAY), NOW(), NOW()),
(2, 7,  1,  'income',  4500000.00,'Gaji Bulan',           'Gaji bulan ini',                   DATE_SUB(NOW(), INTERVAL 14 DAY), NOW(), NOW()),
(2, 8,  2,  'income',  300000.00, 'Freelance Project',    NULL,                               DATE_SUB(NOW(), INTERVAL 15 DAY), NOW(), NOW()),
(2, 9,  2,  'income',  150000.00, 'Angpao',               NULL,                               DATE_SUB(NOW(), INTERVAL 16 DAY), NOW(), NOW()),
(2, 10, 3,  'income',  75000.00,  'Refund',               'Refund barang',                    DATE_SUB(NOW(), INTERVAL 17 DAY), NOW(), NOW()),
(2, 11, 1,  'income',  500000.00, 'Bonus Kinerja',        NULL,                               DATE_SUB(NOW(), INTERVAL 18 DAY), NOW(), NOW()),
(2, 7,  7,  'expense', 80000.00,  'Beli Buku',            'Beli buku kuliah',                 DATE_SUB(NOW(), INTERVAL 19 DAY), NOW(), NOW()),
(2, 8,  22, 'expense', 250000.00, 'Cicilan',              'Cicilan laptop',                   DATE_SUB(NOW(), INTERVAL 20 DAY), NOW(), NOW()),
(2, 9,  8,  'expense', 75000.00,  'Servis Motor',         NULL,                               DATE_SUB(NOW(), INTERVAL 21 DAY), NOW(), NOW()),
(2, 10, 12, 'expense', 100000.00, 'Listrik & Air',        'Bayar tagihan listrik',            DATE_SUB(NOW(), INTERVAL 22 DAY), NOW(), NOW()),
(2, 11, 2,  'income',  200000.00, 'Komisi Penjualan',     'Komisi jualan online',             DATE_SUB(NOW(), INTERVAL 23 DAY), NOW(), NOW());

-- === JIYAD (user_id=3) ===
INSERT INTO `transactions` (`user_id`, `account_id`, `category_id`, `type`, `amount`, `title`, `description`, `transaction_date`, `created_at`, `updated_at`) VALUES
(3, 13, 5,  'expense', 40000.00,  'Makan di Restoran',    'Makan dengan teman',               DATE_SUB(NOW(), INTERVAL 1 DAY),  NOW(), NOW()),
(3, 14, 8,  'expense', 20000.00,  'Bensin',               NULL,                               DATE_SUB(NOW(), INTERVAL 2 DAY),  NOW(), NOW()),
(3, 15, 11, 'expense', 180000.00, 'Belanja Online',       'Beli gadget',                      DATE_SUB(NOW(), INTERVAL 3 DAY),  NOW(), NOW()),
(3, 16, 5,  'expense', 15000.00,  'Kopi Pagi',            NULL,                               DATE_SUB(NOW(), INTERVAL 4 DAY),  NOW(), NOW()),
(3, 17, 8,  'expense', 10000.00,  'Parkir',               'Parkir kampus',                    DATE_SUB(NOW(), INTERVAL 5 DAY),  NOW(), NOW()),
(3, 13, 15, 'expense', 180000.00, 'Tagihan Internet',     'Bayar IndiHome',                   DATE_SUB(NOW(), INTERVAL 6 DAY),  NOW(), NOW()),
(3, 14, 5,  'expense', 30000.00,  'Makan Siang',          'Makan di kantin',                  DATE_SUB(NOW(), INTERVAL 7 DAY),  NOW(), NOW()),
(3, 15, 14, 'expense', 55000.00,  'Hiburan Bioskop',      NULL,                               DATE_SUB(NOW(), INTERVAL 8 DAY),  NOW(), NOW()),
(3, 16, 6,  'expense', 45000.00,  'Makan Malam',          NULL,                               DATE_SUB(NOW(), INTERVAL 9 DAY),  NOW(), NOW()),
(3, 17, 11, 'expense', 95000.00,  'Belanja Groceries',    NULL,                               DATE_SUB(NOW(), INTERVAL 10 DAY), NOW(), NOW()),
(3, 13, 1,  'income',  4500000.00,'Gaji Bulan',           'Gaji bulan',                       DATE_SUB(NOW(), INTERVAL 14 DAY), NOW(), NOW()),
(3, 14, 2,  'income',  350000.00, 'Freelance Project',    'Project coding',                   DATE_SUB(NOW(), INTERVAL 15 DAY), NOW(), NOW()),
(3, 15, 2,  'income',  100000.00, 'Penjualan Barang',     'Jual tas bekas',                   DATE_SUB(NOW(), INTERVAL 16 DAY), NOW(), NOW()),
(3, 16, 1,  'income',  750000.00, 'Bonus Kinerja',        NULL,                               DATE_SUB(NOW(), INTERVAL 17 DAY), NOW(), NOW()),
(3, 17, 3,  'income',  50000.00,  'Refund',               'Refund dari e-commerce',            DATE_SUB(NOW(), INTERVAL 18 DAY), NOW(), NOW()),
(3, 13, 7,  'expense', 65000.00,  'Beli Buku',            'Beli novel',                       DATE_SUB(NOW(), INTERVAL 19 DAY), NOW(), NOW()),
(3, 14, 18, 'expense', 40000.00,  'Obat-obatan',          NULL,                               DATE_SUB(NOW(), INTERVAL 20 DAY), NOW(), NOW()),
(3, 15, 22, 'expense', 275000.00, 'Cicilan',              'Cicilan motor',                    DATE_SUB(NOW(), INTERVAL 21 DAY), NOW(), NOW()),
(3, 16, 14, 'expense', 30000.00,  'Beli Musik',           'Beli lagu digital',                DATE_SUB(NOW(), INTERVAL 22 DAY), NOW(), NOW()),
(3, 17, 5,  'expense', 28000.00,  'Potong Rambut',        NULL,                               DATE_SUB(NOW(), INTERVAL 23 DAY), NOW(), NOW());

-- === AZKA (user_id=4) ===
INSERT INTO `transactions` (`user_id`, `account_id`, `category_id`, `type`, `amount`, `title`, `description`, `transaction_date`, `created_at`, `updated_at`) VALUES
(4, 19, 5,  'expense', 55000.00,  'Makan di Restoran',    'Makan seafood',                    DATE_SUB(NOW(), INTERVAL 1 DAY),  NOW(), NOW()),
(4, 20, 8,  'expense', 15000.00,  'Bensin',               NULL,                               DATE_SUB(NOW(), INTERVAL 2 DAY),  NOW(), NOW()),
(4, 21, 11, 'expense', 350000.00, 'Belanja Online',       'Beli sepatu baru',                 DATE_SUB(NOW(), INTERVAL 3 DAY),  NOW(), NOW()),
(4, 22, 5,  'expense', 22000.00,  'Kopi Pagi',            NULL,                               DATE_SUB(NOW(), INTERVAL 4 DAY),  NOW(), NOW()),
(4, 23, 9,  'expense', 25000.00,  'Taksi',                'Ke mall',                          DATE_SUB(NOW(), INTERVAL 5 DAY),  NOW(), NOW()),
(4, 19, 15, 'expense', 200000.00, 'Tagihan Internet',     NULL,                               DATE_SUB(NOW(), INTERVAL 6 DAY),  NOW(), NOW()),
(4, 20, 5,  'expense', 28000.00,  'Makan Siang',          NULL,                               DATE_SUB(NOW(), INTERVAL 7 DAY),  NOW(), NOW()),
(4, 21, 14, 'expense', 60000.00,  'Hiburan Bioskop',      NULL,                               DATE_SUB(NOW(), INTERVAL 8 DAY),  NOW(), NOW()),
(4, 22, 6,  'expense', 48000.00,  'Makan Malam',          'Makan di restoran Jepang',         DATE_SUB(NOW(), INTERVAL 9 DAY),  NOW(), NOW()),
(4, 23, 11, 'expense', 110000.00, 'Belanja Groceries',    'Belanja di supermarket',           DATE_SUB(NOW(), INTERVAL 10 DAY), NOW(), NOW()),
(4, 19, 1,  'income',  4500000.00,'Gaji Bulan',           NULL,                               DATE_SUB(NOW(), INTERVAL 14 DAY), NOW(), NOW()),
(4, 20, 2,  'income',  400000.00, 'Freelance Project',    'Project desain',                   DATE_SUB(NOW(), INTERVAL 15 DAY), NOW(), NOW()),
(4, 21, 2,  'income',  125000.00, 'Komisi Penjualan',     NULL,                               DATE_SUB(NOW(), INTERVAL 16 DAY), NOW(), NOW()),
(4, 22, 3,  'income',  100000.00, 'Refund',               NULL,                               DATE_SUB(NOW(), INTERVAL 17 DAY), NOW(), NOW()),
(4, 23, 1,  'income',  800000.00, 'Bonus Kinerja',        NULL,                               DATE_SUB(NOW(), INTERVAL 18 DAY), NOW(), NOW()),
(4, 19, 7,  'expense', 55000.00,  'Beli Buku',            'Buku komik',                       DATE_SUB(NOW(), INTERVAL 19 DAY), NOW(), NOW()),
(4, 20, 16, 'expense', 45000.00,  'Servis Motor',         'Ganti ban',                        DATE_SUB(NOW(), INTERVAL 20 DAY), NOW(), NOW()),
(4, 21, 22, 'expense', 300000.00, 'Cicilan',              'Cicilan rumah',                    DATE_SUB(NOW(), INTERVAL 21 DAY), NOW(), NOW()),
(4, 22, 12, 'expense', 85000.00,  'Listrik & Air',        NULL,                               DATE_SUB(NOW(), INTERVAL 22 DAY), NOW(), NOW()),
(4, 23, 2,  'income',  175000.00, 'Tunjangan',            'Tunjangan transport',              DATE_SUB(NOW(), INTERVAL 23 DAY), NOW(), NOW());

-- === FIRA (user_id=5) ===
INSERT INTO `transactions` (`user_id`, `account_id`, `category_id`, `type`, `amount`, `title`, `description`, `transaction_date`, `created_at`, `updated_at`) VALUES
(5, 25, 5,  'expense', 38000.00,  'Makan di Restoran',    NULL,                               DATE_SUB(NOW(), INTERVAL 1 DAY),  NOW(), NOW()),
(5, 26, 8,  'expense', 18000.00,  'Bensin',               NULL,                               DATE_SUB(NOW(), INTERVAL 2 DAY),  NOW(), NOW()),
(5, 27, 11, 'expense', 220000.00, 'Belanja Online',       'Beli skincare',                    DATE_SUB(NOW(), INTERVAL 3 DAY),  NOW(), NOW()),
(5, 28, 5,  'expense', 18000.00,  'Kopi Pagi',            NULL,                               DATE_SUB(NOW(), INTERVAL 4 DAY),  NOW(), NOW()),
(5, 29, 8,  'expense', 12000.00,  'Parkir',               NULL,                               DATE_SUB(NOW(), INTERVAL 5 DAY),  NOW(), NOW()),
(5, 25, 15, 'expense', 160000.00, 'Tagihan Internet',     NULL,                               DATE_SUB(NOW(), INTERVAL 6 DAY),  NOW(), NOW()),
(5, 26, 5,  'expense', 22000.00,  'Makan Siang',          NULL,                               DATE_SUB(NOW(), INTERVAL 7 DAY),  NOW(), NOW()),
(5, 27, 14, 'expense', 50000.00,  'Hiburan Bioskop',      'Nonton film',                      DATE_SUB(NOW(), INTERVAL 8 DAY),  NOW(), NOW()),
(5, 28, 6,  'expense', 42000.00,  'Makan Malam',          NULL,                               DATE_SUB(NOW(), INTERVAL 9 DAY),  NOW(), NOW()),
(5, 29, 11, 'expense', 78000.00,  'Belanja Groceries',    'Bahan masak',                      DATE_SUB(NOW(), INTERVAL 10 DAY), NOW(), NOW()),
(5, 25, 1,  'income',  4500000.00,'Gaji Bulan',           'Gaji bulan ini',                   DATE_SUB(NOW(), INTERVAL 14 DAY), NOW(), NOW()),
(5, 26, 2,  'income',  250000.00, 'Freelance Project',    NULL,                               DATE_SUB(NOW(), INTERVAL 15 DAY), NOW(), NOW()),
(5, 27, 2,  'income',  200000.00, 'Angpao',               'Angpao dari ortu',                 DATE_SUB(NOW(), INTERVAL 16 DAY), NOW(), NOW()),
(5, 28, 3,  'income',  65000.00,  'Refund',               'Refund barang',                    DATE_SUB(NOW(), INTERVAL 17 DAY), NOW(), NOW()),
(5, 29, 1,  'income',  600000.00, 'Bonus Kinerja',        NULL,                               DATE_SUB(NOW(), INTERVAL 18 DAY), NOW(), NOW()),
(5, 25, 7,  'expense', 70000.00,  'Beli Buku',            'Buku resep masak',                 DATE_SUB(NOW(), INTERVAL 19 DAY), NOW(), NOW()),
(5, 26, 17, 'expense', 45000.00,  'Potong Rambut',        NULL,                               DATE_SUB(NOW(), INTERVAL 20 DAY), NOW(), NOW()),
(5, 27, 22, 'expense', 250000.00, 'Cicilan',              'Cicilan laptop',                   DATE_SUB(NOW(), INTERVAL 21 DAY), NOW(), NOW()),
(5, 28, 12, 'expense', 95000.00,  'Listrik & Air',        'Bayar listrik',                    DATE_SUB(NOW(), INTERVAL 22 DAY), NOW(), NOW()),
(5, 29, 5,  'expense', 20000.00,  'Makan Siang',          NULL,                               DATE_SUB(NOW(), INTERVAL 23 DAY), NOW(), NOW());

-- -----------------------------------------------------------
-- TRANSACTION_TAGS (Pivot - beberapa sample)
-- -----------------------------------------------------------
INSERT INTO `transaction_tags` (`transaction_id`, `tag_id`, `created_at`, `updated_at`) VALUES
-- Vasha: transaksi 1 (Makan di Restoran) -> Food, Personal
(1,  4,  NOW(), NOW()),
(1,  9,  NOW(), NOW()),
-- Vasha: transaksi 3 (Belanja Online) -> Shopping
(3,  3,  NOW(), NOW()),
-- Vasha: transaksi 7 (Tagihan Internet) -> Utilities, Recurring
(6,  2,  NOW(), NOW()),
(6,  6,  NOW(), NOW()),
-- Vasha: transaksi 11 (Gaji) -> Work, Business
(11, 10, NOW(), NOW()),
(11, 11, NOW(), NOW()),
-- Dayuk: transaksi 21 (Makan) -> Food
(21, 17, NOW(), NOW()),
-- Dayuk: transaksi 23 (Belanja Online) -> Shopping
(23, 16, NOW(), NOW()),
-- Dayuk: transaksi 30 (Gaji) -> Work
(30, 24, NOW(), NOW()),
-- Jiyad: transaksi 41 (Makan) -> Food
(41, 30, NOW(), NOW()),
-- Jiyad: transaksi 50 (Gaji) -> Work
(50, 37, NOW(), NOW()),
-- Azka: transaksi 61 (Belanja Online) -> Shopping
(63, 42, NOW(), NOW()),
-- Azka: transaksi 70 (Gaji) -> Work
(70, 50, NOW(), NOW()),
-- Fira: transaksi 81 (Belanja Online) -> Shopping
(83, 55, NOW(), NOW()),
-- Fira: transaksi 90 (Gaji) -> Work
(90, 63, NOW(), NOW());