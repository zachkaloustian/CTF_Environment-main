<?php

namespace Database\Factories;

use App\Enums\ChallengeDifficulty;
use App\Models\ChallengeCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChallengeVersion>
 */
class ChallengeVersionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = ChallengeCategory::inRandomOrder()->first();

        return [
            'description_md' => 'Description: '.fake()->sentence(10),
            'difficulty' => ChallengeDifficulty::cases()[array_rand(ChallengeDifficulty::cases())],
            'points' => 500, // Fixed points instead of random
            'flag' => fake()->regexify('ECPI-[A-Z]{4}-[0-9]{4}'),
            'tags' => strtolower($category->name).' challenge', // Tags reflect the category
            'challenge_category_id' => $category->id,
            'max_attempts' => mt_rand(1, 6) * 10,
            'author_id' => User::inRandomOrder()->value('id'),
            'est_time_to_solve' => mt_rand(1, 6) * 10,
            'solution_md' => 'Solution: '.fake()->sentence(10),
        ];
    }
}
