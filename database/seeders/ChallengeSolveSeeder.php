<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChallengeSolveSeeder extends Seeder
{
    public function run(): void
    {
        $challenge = DB::table('challenges')->first();

        DB::table('challenge_solves')->insert([
            [
                'user_id' => 2,
                'challenge_id' => $challenge->id,
                'challenge_title' => $challenge->title,
                'points' => $challenge->points,
            ],
        ]);
    }
}
