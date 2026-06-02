<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('categories')->insert([
            ['user_id' => null, 'name' => 'Makan & Minuman', 'type' => 'expense', 'icon' => 'utensils', 'color' => '#f97316', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => null, 'name' => 'Kopi & Snack', 'type' => 'expense', 'icon' => 'coffee', 'color' => '#d97706', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => null, 'name' => 'Restoran', 'type' => 'expense', 'icon' => 'drumstick-bite', 'color' => '#ea580c', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => null, 'name' => 'Transportasi', 'type' => 'expense', 'icon' => 'car', 'color' => '#0ea5e9', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => null, 'name' => 'Bensin', 'type' => 'expense', 'icon' => 'gas-pump', 'color' => '#06b6d4', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => null, 'name' => 'Taksi & Ojek', 'type' => 'expense', 'icon' => 'taxi', 'color' => '#14b8a6', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => null, 'name' => 'Belanja Barang', 'type' => 'expense', 'icon' => 'shopping-bag', 'color' => '#8b5cf6', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => null, 'name' => 'Pakaian & Fashion', 'type' => 'expense', 'icon' => 'shirt', 'color' => '#d946ef', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => null, 'name' => 'Elektronik', 'type' => 'expense', 'icon' => 'laptop', 'color' => '#6366f1', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => null, 'name' => 'Listrik & Air', 'type' => 'expense', 'icon' => 'bolt', 'color' => '#eab308', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => null, 'name' => 'Internet & Telepon', 'type' => 'expense', 'icon' => 'wifi', 'color' => '#14b8a6', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => null, 'name' => 'Sewa Rumah', 'type' => 'expense', 'icon' => 'home', 'color' => '#f43f5e', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => null, 'name' => 'Hiburan & Film', 'type' => 'expense', 'icon' => 'film', 'color' => '#06b6d4', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => null, 'name' => 'Gaming', 'type' => 'expense', 'icon' => 'gamepad', 'color' => '#8b5cf6', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => null, 'name' => 'Buku & Musik', 'type' => 'expense', 'icon' => 'book', 'color' => '#f59e0b', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => null, 'name' => 'Kesehatan', 'type' => 'expense', 'icon' => 'heartbeat', 'color' => '#ef4444', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => null, 'name' => 'Gym & Olahraga', 'type' => 'expense', 'icon' => 'dumbbell', 'color' => '#06b6d4', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => null, 'name' => 'Obat & Vitamin', 'type' => 'expense', 'icon' => 'pills', 'color' => '#10b981', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => null, 'name' => 'Pendidikan', 'type' => 'expense', 'icon' => 'graduation-cap', 'color' => '#3b82f6', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => null, 'name' => 'Kursus & Workshop', 'type' => 'expense', 'icon' => 'chalkboard-user', 'color' => '#6366f1', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => null, 'name' => 'Asuransi', 'type' => 'expense', 'icon' => 'shield', 'color' => '#8b5cf6', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => null, 'name' => 'Cicilan', 'type' => 'expense', 'icon' => 'credit-card', 'color' => '#f43f5e', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => null, 'name' => 'Hadiah & Ucapan', 'type' => 'expense', 'icon' => 'gift', 'color' => '#f43f5e', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => null, 'name' => 'Donasi & Zakat', 'type' => 'expense', 'icon' => 'hand-holding-heart', 'color' => '#10b981', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => null, 'name' => 'Tabungan', 'type' => 'expense', 'icon' => 'piggy-bank', 'color' => '#22c55e', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => null, 'name' => 'Lain-lain', 'type' => 'expense', 'icon' => 'ellipsis', 'color' => '#6b7280', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('categories')->whereIn('name', [
            'Makan & Minuman',
            'Kopi & Snack',
            'Restoran',
            'Transportasi',
            'Bensin',
            'Taksi & Ojek',
            'Belanja Barang',
            'Pakaian & Fashion',
            'Elektronik',
            'Listrik & Air',
            'Internet & Telepon',
            'Sewa Rumah',
            'Hiburan & Film',
            'Gaming',
            'Buku & Musik',
            'Kesehatan',
            'Gym & Olahraga',
            'Obat & Vitamin',
            'Pendidikan',
            'Kursus & Workshop',
            'Asuransi',
            'Cicilan',
            'Hadiah & Ucapan',
            'Donasi & Zakat',
            'Tabungan',
            'Lain-lain',
        ])->delete();
    }
};
