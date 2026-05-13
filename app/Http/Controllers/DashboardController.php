<?php

namespace App\Http\Controllers;

use App\Models\ChallengeCategory;
use App\Models\ChallengeSolve;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Show the user dashboard with real stats
     */
    public function index()
    {
        $user = Auth::user();

        // Safety check - should not happen due to middleware, but handle gracefully for tests
        if (! $user) {
            return redirect()->route('login');
        }

        /*
        User stats
        */

        // Total points
        $totalPoints = ChallengeSolve::where('user_id', $user->id)->sum('points');

        // Number of challenges solved
        $totalSolves = ChallengeSolve::where('user_id', $user->id)->count();

        // Ranking: count how many users have MORE points
        $rank = ChallengeSolve::selectRaw('user_id, SUM(points) AS total')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->get()
            ->pluck('user_id')
            ->search($user->id);

        // If user has no solves, default rank = 1
        $rank = $rank === false ? 1 : $rank + 1;

        /*
        recent solves
        */

        $recentSolves = ChallengeSolve::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($solve) {
                return [
                    'challenge' => $solve->challenge_title,
                    'points' => $solve->points,
                    'time' => $solve->created_at->diffForHumans(),
                ];
            });

        /*
        Category list (STATIC)
        */

        $categories = ChallengeCategory::all()->map(function ($cat) {
            return [
                'name' => $cat->name,
                'icon' => $cat->icon ?? 'category',
            ];
        });

        /*
        Points per Category
        (Latest version of each challenge)
        */

        $pointsPerCategory = ChallengeSolve::selectRaw('
                challenge_categories.name AS category,
                SUM(challenge_solves.points) AS total_points
            ')
            // join challenge_versions USING challenge_id — NOT challenge_version_id
            ->join('challenge_versions', function ($join) {
                $join->on('challenge_versions.challenge_id', '=', 'challenge_solves.challenge_id')
                    ->whereRaw('
                        challenge_versions.version = (
                            SELECT MAX(version)
                            FROM challenge_versions AS cv
                            WHERE cv.challenge_id = challenge_versions.challenge_id
                        )
                    ');
            })
            ->join('challenge_categories', 'challenge_categories.id', '=', 'challenge_versions.challenge_category_id')
            ->where('challenge_solves.user_id', $user->id)
            ->groupBy('challenge_categories.name')
            ->orderByDesc('total_points')
            ->get();

        /*
         Return to frontend
        */

        return Inertia::render('Dashboard', [
            'stats' => [
                'points' => $totalPoints,
                'rank' => $rank,
                'solves' => $totalSolves,
            ],

            'categories' => $categories,
            'recentSolves' => $recentSolves,
            'pointsByCategory' => $pointsPerCategory,
        ]);
    }
}
