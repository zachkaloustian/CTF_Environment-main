<?php

use App\Http\Controllers\ChallengeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    Route::redirect('settings', '/settings/profile');

    Route::get('admin/challenges', [ChallengeController::class, 'edit'])->name('challenge.edit');
    
});
