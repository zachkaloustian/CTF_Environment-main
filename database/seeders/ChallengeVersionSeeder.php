<?php

namespace Database\Seeders;
use App\Models\Challenge;
use App\Models\ChallengeVersion;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChallengeVersionSeeder extends Seeder
{
    public function run(): void
    {
        $challenges = DB::table('challenges')->get();
        
        foreach ($challenges as $challenge) {
            ChallengeVersion::factory()->create([
                'challenge_id' => $challenge->id,
                'tags' => strtolower(str_replace(' ', '_', $challenge->title)),
                'solution_md' => "Solution for **{$challenge->title}**",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        //$challenge = ChallengeFactory::create(['title' => 'SQL Injection']);
        ChallengeVersion::factory()
            ->for(Challenge::factory()->create(['title' => 'SQL Injection']))
            ->state([
                'challenge_category_id' => 1,
                'difficulty' => 'easy',
                'description_md' => 'Exploit a basic SQL injection vulnerability to bypass login.'
            ])
            ->count(5)
            ->create();
    }
}
