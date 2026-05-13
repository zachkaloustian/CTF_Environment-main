<?php

use App\Models\Challenge;
use App\Models\ChallengeCategory;
use App\Models\ChallengeSolve;
use App\Models\ChallengeVersion;
use App\Models\User;

test('challenges index page is displayed', function () {
    $user = User::factory()->create();
    $category = ChallengeCategory::factory()->create();
    $challenge = Challenge::factory()->create();
    $version = ChallengeVersion::factory()->create([
        'challenge_id' => $challenge->id,
        'challenge_category_id' => $category->id,
    ]);

    $response = $this
        ->actingAs($user)
        ->get(route('challenges.index'));

    $response->assertOk();
});

test('challenges index with category filter', function () {
    $user = User::factory()->create();
    $category = ChallengeCategory::factory()->create(['name' => 'Web']);
    $challenge = Challenge::factory()->create();
    $version = ChallengeVersion::factory()->create([
        'challenge_id' => $challenge->id,
        'challenge_category_id' => $category->id,
    ]);

    $response = $this
        ->actingAs($user)
        ->get(route('challenges.index', ['category' => 'Web']));

    $response->assertOk();
});

test('challenge show page is displayed', function () {
    $user = User::factory()->create();
    $category = ChallengeCategory::factory()->create();
    $challenge = Challenge::factory()->create();
    $version = ChallengeVersion::factory()->create([
        'challenge_id' => $challenge->id,
        'challenge_category_id' => $category->id,
    ]);

    $response = $this
        ->actingAs($user)
        ->get(route('challenges.show', $challenge));

    $response->assertOk();
});

test('submit correct flag', function () {
    $user = User::factory()->create();
    $category = ChallengeCategory::factory()->create();
    $challenge = Challenge::factory()->create();
    $version = ChallengeVersion::factory()->create([
        'challenge_id' => $challenge->id,
        'challenge_category_id' => $category->id,
        'flag' => 'correct_flag',
    ]);

    $response = $this
        ->actingAs($user)
        ->post(route('challenges.submit', $challenge), [
            'flag' => 'correct_flag',
        ]);

    $response->assertRedirect();
});

test('submit incorrect flag', function () {
    $user = User::factory()->create();
    $category = ChallengeCategory::factory()->create();
    $challenge = Challenge::factory()->create();
    $version = ChallengeVersion::factory()->create([
        'challenge_id' => $challenge->id,
        'challenge_category_id' => $category->id,
        'flag' => 'correct_flag',
    ]);

    $response = $this
        ->actingAs($user)
        ->post(route('challenges.submit', $challenge), [
            'flag' => 'wrong_flag',
        ]);

    $response->assertRedirect();
    $response->assertSessionHas('correct', false);
});

test('submit flag when already solved', function () {
    $user = User::factory()->create();
    $category = ChallengeCategory::factory()->create();
    $challenge = Challenge::factory()->create();
    $version = ChallengeVersion::factory()->create([
        'challenge_id' => $challenge->id,
        'challenge_category_id' => $category->id,
        'flag' => 'correct_flag',
    ]);
    ChallengeSolve::withoutEvents(function () use ($user, $challenge, $version) {
        ChallengeSolve::create([
            'user_id' => $user->id,
            'challenge_id' => $challenge->id,
            'challenge_title' => $challenge->title,
            'points' => $version->points,
        ]);
    });

    $response = $this
        ->actingAs($user)
        ->post(route('challenges.submit', $challenge), [
            'flag' => 'correct_flag',
        ]);

    $response->assertRedirect();
});

test('create challenge form is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get(route('challenges.create'));

    $response->assertOk();
});

test('store challenge successfully', function () {
    $user = User::factory()->create();
    $category = ChallengeCategory::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from(route('challenges.create'))
        ->post(route('challenges.store'), [
            'title' => 'New Challenge',
            'description' => 'Description',
            'difficulty' => 'easy',
            'points' => 100,
            'category_id' => $category->id,
            'flag' => 'ECPI-TEST-1234',
            'tags' => 'test',
            'est_time' => 30,
        ]);

    $response->assertRedirect(route('challenges.index'));
});

test('store challenge validation errors', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post(route('challenges.store'), [
            'title' => '',
            'description' => '',
            'difficulty' => 'invalid',
            'points' => 0,
            'category_id' => 999,
            'flag' => '',
        ]);

    $response->assertSessionHasErrors(['title', 'description', 'difficulty', 'points', 'category_id', 'flag']);
});
