<?php

use App\Models\Challenge;
use App\Models\ChallengeCategory;
use App\Models\ChallengeSolve;
use App\Models\ChallengeVersion;
use App\Models\User;
use App\Models\UserBadge;
use App\Services\BadgeAwardService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('awards first solve badge', function () {
    \DB::table('badges')->insert([
        ['id' => 1, 'name' => 'First Solve', 'description' => 'Awarded for solving your first challenge.', 'type' => 'first_solve', 'criteria' => null, 'icon' => 'trophy', 'created_at' => now(), 'updated_at' => now()],
        ['id' => 2, 'name' => '10 Solves', 'description' => 'Awarded for solving 10 challenges.', 'type' => 'solve_count', 'criteria' => json_encode(['count' => 10]), 'icon' => 'star', 'created_at' => now(), 'updated_at' => now()],
        ['id' => 3, 'name' => '50 Solves', 'description' => 'Awarded for solving 50 challenges.', 'type' => 'solve_count', 'criteria' => json_encode(['count' => 50]), 'icon' => 'medal', 'created_at' => now(), 'updated_at' => now()],
        ['id' => 4, 'name' => '100 Solves', 'description' => 'Awarded for solving 100 challenges.', 'type' => 'solve_count', 'criteria' => json_encode(['count' => 100]), 'icon' => 'crown', 'created_at' => now(), 'updated_at' => now()],
        ['id' => 5, 'name' => 'Easy Master', 'description' => 'Awarded for solving all easy challenges.', 'type' => 'difficulty_complete', 'criteria' => json_encode(['difficulty' => 'easy']), 'icon' => 'shield', 'created_at' => now(), 'updated_at' => now()],
        ['id' => 6, 'name' => 'Medium Master', 'description' => 'Awarded for solving all medium challenges.', 'type' => 'difficulty_complete', 'criteria' => json_encode(['difficulty' => 'medium']), 'icon' => 'shield-alt', 'created_at' => now(), 'updated_at' => now()],
        ['id' => 7, 'name' => 'Hard Master', 'description' => 'Awarded for solving all hard challenges.', 'type' => 'difficulty_complete', 'criteria' => json_encode(['difficulty' => 'hard']), 'icon' => 'shield-check', 'created_at' => now(), 'updated_at' => now()],
    ]);

    $user = User::factory()->create();
    $category = ChallengeCategory::factory()->create();
    $challenge = Challenge::factory()->create();
    $version = ChallengeVersion::factory()->create([
        'challenge_id' => $challenge->id,
        'challenge_category_id' => $category->id,
    ]);

    // Initially no solves
    expect(ChallengeSolve::count())->toBe(0);
    expect(UserBadge::count())->toBe(0);

    // Create a solve
    ChallengeSolve::create([
        'user_id' => $user->id,
        'challenge_id' => $challenge->id,
        'challenge_title' => $challenge->title,
        'points' => $version->points,
    ]);

    // Award badges
    $service = app(BadgeAwardService::class);
    $service->awardBadgesForUser($user);

    // Should have awarded first solve badge
    expect(UserBadge::where('user_id', $user->id)->where('badge_id', 1)->exists())->toBe(true);
});

test('awards solve count badges', function () {
    \DB::table('badges')->insert([
        ['id' => 1, 'name' => 'First Solve', 'description' => 'Awarded for solving your first challenge.', 'type' => 'first_solve', 'criteria' => null, 'icon' => 'trophy', 'created_at' => now(), 'updated_at' => now()],
        ['id' => 2, 'name' => '10 Solves', 'description' => 'Awarded for solving 10 challenges.', 'type' => 'solve_count', 'criteria' => json_encode(['count' => 10]), 'icon' => 'star', 'created_at' => now(), 'updated_at' => now()],
        ['id' => 3, 'name' => '50 Solves', 'description' => 'Awarded for solving 50 challenges.', 'type' => 'solve_count', 'criteria' => json_encode(['count' => 50]), 'icon' => 'medal', 'created_at' => now(), 'updated_at' => now()],
        ['id' => 4, 'name' => '100 Solves', 'description' => 'Awarded for solving 100 challenges.', 'type' => 'solve_count', 'criteria' => json_encode(['count' => 100]), 'icon' => 'crown', 'created_at' => now(), 'updated_at' => now()],
        ['id' => 5, 'name' => 'Easy Master', 'description' => 'Awarded for solving all easy challenges.', 'type' => 'difficulty_complete', 'criteria' => json_encode(['difficulty' => 'easy']), 'icon' => 'shield', 'created_at' => now(), 'updated_at' => now()],
        ['id' => 6, 'name' => 'Medium Master', 'description' => 'Awarded for solving all medium challenges.', 'type' => 'difficulty_complete', 'criteria' => json_encode(['difficulty' => 'medium']), 'icon' => 'shield-alt', 'created_at' => now(), 'updated_at' => now()],
        ['id' => 7, 'name' => 'Hard Master', 'description' => 'Awarded for solving all hard challenges.', 'type' => 'difficulty_complete', 'criteria' => json_encode(['difficulty' => 'hard']), 'icon' => 'shield-check', 'created_at' => now(), 'updated_at' => now()],
    ]);

    $user = User::factory()->create();
    $category = ChallengeCategory::factory()->create();

    // Create 10 challenges
    for ($i = 0; $i < 10; $i++) {
        $challenge = Challenge::factory()->create(['title' => 'Challenge '.$i]);
        $version = ChallengeVersion::factory()->create([
            'challenge_id' => $challenge->id,
            'challenge_category_id' => $category->id,
        ]);
        ChallengeSolve::create([
            'user_id' => $user->id,
            'challenge_id' => $challenge->id,
            'challenge_title' => $challenge->title,
            'points' => $version->points,
        ]);
    }

    // Award badges
    $service = app(BadgeAwardService::class);
    $service->awardBadgesForUser($user);

    // Should have awarded 10 solves badge
    expect(UserBadge::where('user_id', $user->id)->where('badge_id', 2)->exists())->toBe(true);
});

test('does not award duplicate badges', function () {
    \DB::table('badges')->insert([
        ['id' => 1, 'name' => 'First Solve', 'description' => 'Awarded for solving your first challenge.', 'type' => 'first_solve', 'criteria' => null, 'icon' => 'trophy', 'created_at' => now(), 'updated_at' => now()],
        ['id' => 2, 'name' => '10 Solves', 'description' => 'Awarded for solving 10 challenges.', 'type' => 'solve_count', 'criteria' => json_encode(['count' => 10]), 'icon' => 'star', 'created_at' => now(), 'updated_at' => now()],
        ['id' => 3, 'name' => '50 Solves', 'description' => 'Awarded for solving 50 challenges.', 'type' => 'solve_count', 'criteria' => json_encode(['count' => 50]), 'icon' => 'medal', 'created_at' => now(), 'updated_at' => now()],
        ['id' => 4, 'name' => '100 Solves', 'description' => 'Awarded for solving 100 challenges.', 'type' => 'solve_count', 'criteria' => json_encode(['count' => 100]), 'icon' => 'crown', 'created_at' => now(), 'updated_at' => now()],
        ['id' => 5, 'name' => 'Easy Master', 'description' => 'Awarded for solving all easy challenges.', 'type' => 'difficulty_complete', 'criteria' => json_encode(['difficulty' => 'easy']), 'icon' => 'shield', 'created_at' => now(), 'updated_at' => now()],
        ['id' => 6, 'name' => 'Medium Master', 'description' => 'Awarded for solving all medium challenges.', 'type' => 'difficulty_complete', 'criteria' => json_encode(['difficulty' => 'medium']), 'icon' => 'shield-alt', 'created_at' => now(), 'updated_at' => now()],
        ['id' => 7, 'name' => 'Hard Master', 'description' => 'Awarded for solving all hard challenges.', 'type' => 'difficulty_complete', 'criteria' => json_encode(['difficulty' => 'hard']), 'icon' => 'shield-check', 'created_at' => now(), 'updated_at' => now()],
    ]);

    $user = User::factory()->create();
    $category = ChallengeCategory::factory()->create();
    $challenge = Challenge::factory()->create();
    $version = ChallengeVersion::factory()->create([
        'challenge_id' => $challenge->id,
        'challenge_category_id' => $category->id,
    ]);

    // Create solve
    ChallengeSolve::create([
        'user_id' => $user->id,
        'challenge_id' => $challenge->id,
        'challenge_title' => $challenge->title,
        'points' => $version->points,
    ]);

    // Award badges twice
    $service = app(BadgeAwardService::class);
    $service->awardBadgesForUser($user);
    $service->awardBadgesForUser($user);

    // Should have only one first solve badge
    expect(UserBadge::where('user_id', $user->id)->where('badge_id', 1)->count())->toBe(1);
});
