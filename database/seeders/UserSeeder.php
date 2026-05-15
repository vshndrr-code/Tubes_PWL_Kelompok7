<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 5 group members
        $members = [
            [
                'name' => 'Vasha',
                'email' => 'vasha@moma.local',
            ],
            [
                'name' => 'Dayuk',
                'email' => 'dayuk@moma.local',
            ],
            [
                'name' => 'Jiyad',
                'email' => 'jiyad@moma.local',
            ],
            [
                'name' => 'Azka',
                'email' => 'azka@moma.local',
            ],
            [
                'name' => 'Fira',
                'email' => 'fira@moma.local',
            ],
        ];

        $password = Hash::make('kelompok7');

        foreach ($members as $member) {
            User::updateOrCreate(
                ['email' => $member['email']],
                [
                    'name' => $member['name'],
                    'email_verified_at' => now(),
                    'password' => $password,
                    'remember_token' => \Illuminate\Support\Str::random(10),
                ]
            );
        }
    }
}
