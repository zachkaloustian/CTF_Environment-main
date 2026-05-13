<?php

namespace App\Services;

use App\Enums\BadgeType;
use App\Models\Badge;
use App\Models\ChallengeSolve;
use App\Models\User;
use App\Models\UserBadge;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BadgeAwardService
{
    public function awardBadgesForUser(User $user): void
    {
        Log::info("Awarding badges for user {$user->id} ({$user->name})");
        $badges = Badge::all();
        $awarded = false;

        foreach ($badges as $badge) {
            Log::info("Checking badge: {$badge->name} for user {$user->id}");
            if ($this->userQualifiesForBadge($user, $badge)) {
                Log::info("User qualifies for badge: {$badge->name}");
                $this->awardBadgeToUser($user, $badge);
                $awarded = true;
            } else {
                Log::info("User does not qualify for badge: {$badge->name}");
            }
        }

        // Notification handling moved to ChallengeController

    }

    private function userQualifiesForBadge(User $user, Badge $badge): bool
    {
        // Check if user already has this badge
        if (UserBadge::where('user_id', $user->id)->where('badge_id', $badge->id)->exists()) {
            return false;
        }

        $qualifies = match ($badge->type) {
            BadgeType::FIRST_SOLVE => $this->checkFirstSolve($user),
            BadgeType::SOLVE_COUNT => $this->checkSolveCount($user, $badge->criteria['count'] ?? 0),
            BadgeType::DIFFICULTY_COMPLETE => $this->checkDifficultyComplete($user, $badge->criteria['difficulty'] ?? ''),
            default => false,
        };

        return $qualifies;
    }

    private function checkFirstSolve(User $user): bool
    {
        $exists = ChallengeSolve::where('user_id', $user->id)->exists();
        Log::info("checkFirstSolve for user {$user->id}: {$exists}");

        return $exists;
    }

    private function checkSolveCount(User $user, int $count): bool
    {
        return ChallengeSolve::where('user_id', $user->id)->count() >= $count;
    }

    private function checkDifficultyComplete(User $user, string $difficulty): bool
    {
        \Log::info("Checking difficulty complete for user {$user->id}, difficulty: {$difficulty}");
        // Get all challenge_ids of latest versions with this difficulty
        $challengeIds = DB::table('challenge_versions')
            ->select('challenge_versions.challenge_id')
            ->where('difficulty', $difficulty)
            ->whereIn('version', function ($query) {
                $query->selectRaw('max(version)')
                    ->from('challenge_versions as cv2')
                    ->groupBy('cv2.challenge_id');
            })
            ->pluck('challenge_id');

        $totalChallenges = $challengeIds->count();
        \Log::info("Total challenges for difficulty {$difficulty}: {$totalChallenges}");

        // Get solved challenge_ids by user
        $solvedChallengeIds = ChallengeSolve::where('user_id', $user->id)
            ->whereIn('challenge_id', $challengeIds)
            ->pluck('challenge_id')
            ->unique();

        $solvedCount = $solvedChallengeIds->count();
        \Log::info("Solved challenges for difficulty {$difficulty}: {$solvedCount}");

        $result = $solvedCount >= $totalChallenges && $totalChallenges > 0;
        \Log::info("Difficulty complete result: {$result}");

        return $result;
    }

    private function awardBadgeToUser(User $user, Badge $badge): void
    {
        Log::info("Awarding badge {$badge->name} to user {$user->id}");
        try {
            UserBadge::create([
                'user_id' => $user->id,
                'badge_id' => $badge->id,
                'awarded_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::warning("Failed to award badge {$badge->name} to user {$user->id}: ".$e->getMessage());
        }
    }
}
