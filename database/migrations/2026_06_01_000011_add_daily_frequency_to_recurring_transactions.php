<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL: modify the enum to include 'daily'
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE recurring_transactions MODIFY frequency ENUM('daily', 'weekly', 'monthly', 'yearly') DEFAULT 'monthly'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE recurring_transactions MODIFY frequency ENUM('weekly', 'monthly', 'yearly') DEFAULT 'monthly'");
        }
    }
};
