<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChallengeCategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('challenge_categories')->insert([
            ['name' => 'Web Exploitation'],     // ID 1
            ['name' => 'Cryptography'],         // ID 2
            ['name' => 'Forensics'],            // ID 3
            ['name' => 'Reverse Engineering'],  // ID 4
            ['name' => 'Pwn'],                  // ID 5
            ['name' => 'OSINT'],                // ID 6
        ]);
    }
}
