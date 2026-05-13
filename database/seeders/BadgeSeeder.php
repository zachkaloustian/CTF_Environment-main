<?php

namespace Database\Seeders;

use App\Enums\BadgeType;
use App\Models\Badge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Badge::create([
            'name' => 'First Solve',
            'description' => 'Awarded for solving your first challenge.',
            'icon' => 'trophy',
            'type' => BadgeType::FIRST_SOLVE,
            'criteria' => null,
        ]);

        Badge::create([
            'name' => '10 Solves',
            'description' => 'Awarded for solving 10 challenges.',
            'icon' => 'star',
            'type' => BadgeType::SOLVE_COUNT,
            'criteria' => ['count' => 10],
        ]);

        Badge::create([
            'name' => '50 Solves',
            'description' => 'Awarded for solving 50 challenges.',
            'icon' => 'medal',
            'type' => BadgeType::SOLVE_COUNT,
            'criteria' => ['count' => 50],
        ]);

        Badge::create([
            'name' => '100 Solves',
            'description' => 'Awarded for solving 100 challenges.',
            'icon' => 'crown',
            'type' => BadgeType::SOLVE_COUNT,
            'criteria' => ['count' => 100],
        ]);

        Badge::create([
            'name' => 'Easy Master',
            'description' => 'Awarded for solving all easy challenges.',
            'icon' => 'shield',
            'type' => BadgeType::DIFFICULTY_COMPLETE,
            'criteria' => ['difficulty' => 'easy'],
        ]);

        Badge::create([
            'name' => 'Medium Master',
            'description' => 'Awarded for solving all medium challenges.',
            'icon' => 'shield-alt',
            'type' => BadgeType::DIFFICULTY_COMPLETE,
            'criteria' => ['difficulty' => 'medium'],
        ]);

        Badge::create([
            'name' => 'Hard Master',
            'description' => 'Awarded for solving all hard challenges.',
            'icon' => 'shield-check',
            'type' => BadgeType::DIFFICULTY_COMPLETE,
            'criteria' => ['difficulty' => 'hard'],
        ]);
    }
}
