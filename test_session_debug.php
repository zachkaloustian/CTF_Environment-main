<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\ChallengeSolve;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

// Clear log file
file_put_contents(storage_path('logs/laravel.log'), '');

// Clear any existing badges and solves for user 2
ChallengeSolve::where('user_id', 2)->delete();
User::find(2)->badges()->detach();

// Start session
Session::start();

// Log session details
Log::info('=== Session Debug Test ===');
Log::info('Session ID: '.Session::getId());

// Create a new solve
$solve = ChallengeSolve::create([
    'user_id' => 2,
    'challenge_id' => 'a0ed2871-8aac-4c1d-b0d4-7ed18bef26c6',
    'challenge_title' => 'Basic SQL Injection',
    'points' => 50,
]);
Log::info("Solve created: {$solve->id}");

// Call the BadgeAwardService
$badgeService = app(\App\Services\BadgeAwardService::class);

// Capture badge count before and after
$beforeCount = \App\Models\UserBadge::where('user_id', 2)->count();
$badgeService->awardBadgesForUser(User::find(2));
$afterCount = \App\Models\UserBadge::where('user_id', 2)->count();
$badgeEarned = $afterCount > $beforeCount;

// Set flash session
Session::flash('showBadgeNotification', $badgeEarned);
Session::flash('correct', true);

// Check session
Log::info('Session before redirect:');
Log::info('  showBadgeNotification: '.(Session::has('showBadgeNotification') ? (Session::get('showBadgeNotification') ? 'true' : 'false') : 'null'));
Log::info('  correct: '.(Session::has('correct') ? (Session::get('correct') ? 'true' : 'false') : 'null'));
Log::info('  All session keys: '.json_encode(array_keys(Session::all())));

// Simulate redirect by regenerating session
Session::save();
Session::start();

Log::info('Session after redirect:');
Log::info('  showBadgeNotification: '.(Session::has('showBadgeNotification') ? (Session::get('showBadgeNotification') ? 'true' : 'false') : 'null'));
Log::info('  correct: '.(Session::has('correct') ? (Session::get('correct') ? 'true' : 'false') : 'null'));
Log::info('  All session keys: '.json_encode(array_keys(Session::all())));

// Cleanup
Session::flush();

echo "Test completed! Check laravel.log for detailed session debug info\n";
