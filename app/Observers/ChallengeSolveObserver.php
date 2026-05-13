<?php

namespace App\Observers;

use App\Models\ChallengeSolve;
use App\Services\BadgeAwardService;

class ChallengeSolveObserver
{
    // Badge awarding moved to controller
    public function created(ChallengeSolve $challengeSolve): void
    {
        \Log::info("ChallengeSolveObserver triggered for solve ID: {$challengeSolve->id}, user: {$challengeSolve->user_id}");
        // app(BadgeAwardService::class)->awardBadgesForUser($challengeSolve->user);
    }
}
