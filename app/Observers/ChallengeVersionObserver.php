<?php

namespace App\Observers;

use App\Models\ChallengeVersion;
use Illuminate\Support\Facades\Log;

class ChallengeVersionObserver
{
    /**
     * Handle the ChallengeVersion "creating" event.
     */
    public function creating(ChallengeVersion $challengeVersion): void
    {
        // Get highest version for challenge
        $currentVersion = ChallengeVersion::where([
            'challenge_id' => $challengeVersion->challenge_id
        ])->max('version');
        // Create this new challenge with higher version
        $challengeVersion->version = ($currentVersion ?? 0) + 1;
        Log::debug($challengeVersion->challenge.':'.$challengeVersion->version.' inserted.');
    }
    /**
     * Handle the ChallengeVersion "created" event.
     */
    public function created(ChallengeVersion $challengeVersion): void
    {
        //
    }

    /**
     * Handle the ChallengeVersion "updated" event.
     */
    public function updated(ChallengeVersion $challengeVersion): void
    {
        //
    }

    /**
     * Handle the ChallengeVersion "deleted" event.
     */
    public function deleted(ChallengeVersion $challengeVersion): void
    {
        //
    }

    /**
     * Handle the ChallengeVersion "restored" event.
     */
    public function restored(ChallengeVersion $challengeVersion): void
    {
        //
    }

    /**
     * Handle the ChallengeVersion "force deleted" event.
     */
    public function forceDeleted(ChallengeVersion $challengeVersion): void
    {
        //
    }
}
