<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@example.com'], // Unique key
            [
                'name' => 'Admin User',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
                'two_factor_confirmed_at' => null,
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Demo Player
        DB::table('users')->updateOrInsert(
            ['email' => 'player@example.com'], // Unique key
            [
                'name' => 'Demo Player',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
                'two_factor_confirmed_at' => null,
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
