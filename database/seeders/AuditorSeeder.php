<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AuditorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'auditor@moma.com'],
            [
                'name' => 'SDGs Auditor',
                'password' => Hash::make('password'),
                'role' => 'auditor',
                'onboarding_completed' => true,
                'currency' => 'IDR',
                'email_verified_at' => now(),
            ]
        );
    }
}
