<?php

use App\Http\Controllers\BadgeController;
use App\Http\Controllers\ChallengeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('CTFLanding', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/badges', [BadgeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('badges.index');

// Include Settings routes
require __DIR__.'/settings.php';

// Scoreboard route
Route::get('/scoreboard', function () {
    return Inertia::render('Scoreboard');
})->name('scoreboard');

// Guidelines Page
Route::get('/guidelines', fn () => Inertia::render('Guidelines'))->name('guidelines');

// CHALLENGES (normal)
Route::get('/challenges', [ChallengeController::class, 'index'])
    ->name('challenges.index');

// CHALLENGE CREATION ROUTES
// Only logged-in users can create new challenges
Route::middleware(['auth'])->group(function () {

    // Show create challenge page
    Route::get('/challenges/create', [ChallengeController::class, 'create'])
        ->name('challenges.create');

    // Save new challenge
    Route::post('/challenges', [ChallengeController::class, 'store'])
        ->name('challenges.store');
});

// Challenge details + flag submission
Route::get('/challenges/{challenge}', [ChallengeController::class, 'show'])
    ->name('challenges.show');

Route::post('/challenges/{challenge}/submit', [ChallengeController::class, 'submitFlag'])
    ->name('challenges.submit');

// TEAM ROUTES
Route::middleware(['auth', 'verified'])->group(function () {
    // Browse teams
    Route::get('/teams', [App\Http\Controllers\TeamController::class, 'index'])
        ->name('teams.index');

    // Create team
    Route::get('/teams/create', [App\Http\Controllers\TeamController::class, 'create'])
        ->name('teams.create');
    Route::post('/teams', [App\Http\Controllers\TeamController::class, 'store'])
        ->name('teams.store');

    // View team
    Route::get('/teams/{team}', [App\Http\Controllers\TeamController::class, 'show'])
        ->name('teams.show');

    // Join team
    Route::post('/teams/join', [App\Http\Controllers\TeamController::class, 'join'])
        ->name('teams.join');

    // Leave team
    Route::post('/teams/{team}/leave', [App\Http\Controllers\TeamController::class, 'leave'])
        ->name('teams.leave');

    // Team management (captain only)
    Route::get('/teams/{team}/manage', [App\Http\Controllers\TeamController::class, 'manage'])
        ->name('teams.manage');
    Route::put('/teams/{team}', [App\Http\Controllers\TeamController::class, 'update'])
        ->name('teams.update');
    Route::post('/teams/{team}/invite', [App\Http\Controllers\TeamController::class, 'invite'])
        ->name('teams.invite');
    Route::delete('/teams/{team}/members/{member}', [App\Http\Controllers\TeamController::class, 'removeMember'])
        ->name('teams.members.remove');
    Route::post('/teams/{team}/transfer-captain/{newCaptain}', [App\Http\Controllers\TeamController::class, 'transferCaptain'])
        ->name('teams.transfer-captain');
    Route::delete('/teams/{team}', [App\Http\Controllers\TeamController::class, 'disband'])
        ->name('teams.disband');
});

// SETTINGS ROUTES (moved from routes/settings.php for route generation)
Route::middleware('auth')->group(function () {
    Route::redirect('settings', '/settings/profile');

    Route::get('settings/profile', [App\Http\Controllers\Settings\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('settings/profile', [App\Http\Controllers\Settings\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('settings/profile', [App\Http\Controllers\Settings\ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('settings/password', [App\Http\Controllers\Settings\PasswordController::class, 'edit'])->name('user-password.edit');
    Route::put('settings/password', [App\Http\Controllers\Settings\PasswordController::class, 'update'])
        ->middleware('throttle:6,1')
        ->name('user-password.update');

    Route::get('settings/appearance', function () {
        return Inertia::render('settings/Appearance');
    })->name('appearance.edit');

    Route::get('settings/two-factor', [App\Http\Controllers\Settings\TwoFactorAuthenticationController::class, 'show'])
        ->name('two-factor.show');
});
