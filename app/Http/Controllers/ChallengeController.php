<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\ChallengeCategory;
use App\Models\ChallengeSolve;
use App\Models\ChallengeVersion;
use App\Models\UserBadge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ChallengeController extends Controller
{
    /**
     * Show all challenges (latest version only)
     */
    public function index(Request $request)
    {
        $categoryFilter = $request->query('category');
        $search = $request->query('search'); // New: Search term
        $difficultyFilter = $request->query('difficulty'); // New: Difficulty filter
        $sortBy = $request->query('sort', 'title'); // New: Sort by field
        $sortDirection = $request->query('direction', 'asc'); // New: Sort direction

        $query = Challenge::query()
            ->with([
                'versions' => function ($q) {
                    $q->orderBy('version', 'desc')->limit(1);
                },
                'versions.category',
            ]);

        if ($categoryFilter) {
            $query->whereHas('versions.category', function ($q) use ($categoryFilter) {
                $q->where('name', $categoryFilter);
            });
        }

        // New: Difficulty filter
        if ($difficultyFilter) {
            $query->whereHas('versions', function ($q) use ($difficultyFilter) {
                $q->where('difficulty', $difficultyFilter);
            });
        }

        // New: Search functionality
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('versions', function ($subQ) use ($search) {
                        $subQ->where('description_md', 'like', "%{$search}%");
                    });
            });
        }

        $challenges = $query->get()->map(function ($challenge) {
            $v = $challenge->versions->first();

            return [
                'id' => $challenge->id,
                'title' => $challenge->title,
                'description' => $v?->description_md ?? '',
                'difficulty' => strtolower($v?->difficulty?->value ?? 'easy'),
                'points' => $v?->points ?? 0,
                'category' => $v?->category?->name ?? 'Uncategorized',
            ];
        });

        // Sort the collection
        $challenges = $challenges->sort(function ($a, $b) use ($sortBy, $sortDirection) {
            $aVal = $a[$sortBy] ?? $a['title'];
            $bVal = $b[$sortBy] ?? $b['title'];
            if ($sortBy === 'difficulty') {
                $order = ['easy' => 1, 'medium' => 2, 'hard' => 3];
                $aVal = $order[$aVal] ?? 0;
                $bVal = $order[$bVal] ?? 0;
            }

            return $sortDirection === 'asc' ? $aVal <=> $bVal : $bVal <=> $aVal;
        })->values(); // Reindex after sorting

        return Inertia::render('Challenges/Index', [
            'challenges' => $challenges,
            'selectedCategory' => $categoryFilter,
            'filters' => [
                'search' => $search,
                'difficulty' => $difficultyFilter,
                'sort' => $sortBy,
                'direction' => $sortDirection,
            ],
        ]);
    }

    /**
     * Show a single challenge + version history
     */
    public function show(Challenge $challenge)
    {
        $latest = $challenge->versions()
            ->orderBy('version', 'desc')
            ->first();

        $versions = $challenge->versions()
            ->orderBy('version', 'desc')
            ->get()
            ->map(function ($v) {
                return [
                    'id' => $v->id,
                    'version' => $v->version,
                    'difficulty' => strtolower($v->difficulty?->value ?? 'easy'),
                    'points' => $v->points,
                    'updated_at' => $v->updated_at->toDateTimeString(),
                ];
            });

        $showBadgeNotification = session('showBadgeNotification');
        $correct = session('correct');

        \Log::info('Show badge notification: '.($showBadgeNotification ? 'true' : 'false'));
        \Log::info('Correct flag: '.($correct ? 'true' : 'false'));

        if ($showBadgeNotification) {
            session()->forget('showBadgeNotification');
            \Log::info('Showing badge notification for user '.Auth::id());
        }
        if ($correct !== null) {
            session()->forget('correct');
        }

        return Inertia::render('Challenges/Show', [
            'challenge' => [
                'id' => $challenge->id,
                'title' => $challenge->title,
                'description' => $latest?->description_md ?? '',
                'difficulty' => strtolower($latest?->difficulty?->value ?? 'easy'),
                'points' => $latest?->points ?? 0,
                'category' => $latest?->category?->name ?? 'Uncategorized',
            ],
            'versions' => $versions,
            'correct' => $correct,
            'showBadgeNotification' => $showBadgeNotification,
        ]);
    }

    /**
     * Submit flag + record solve
     */
    public function submitFlag(Request $request, Challenge $challenge)
    {
        $request->validate([
            'flag' => 'required|string',
        ]);

        $latest = $challenge->versions()
            ->orderBy('version', 'desc')
            ->first();

        $isCorrect = trim($request->flag) === trim($latest?->flag ?? '');
        $badgeEarned = false;

        if ($isCorrect) {
            $already = ChallengeSolve::where('user_id', Auth::id())
                ->where('challenge_id', $challenge->id)
                ->exists();

            if (! $already) {
                \Log::info('Creating ChallengeSolve for user '.Auth::id().", challenge {$challenge->id}");
                ChallengeSolve::create([
                    'user_id' => Auth::id(),
                    'challenge_id' => $challenge->id,
                    'challenge_title' => $challenge->title,
                    'points' => $latest->points,
                ]);
                // Award badges for the user
                $badgeService = app(\App\Services\BadgeAwardService::class);
                // Capture if badges were earned by checking count before and after
                $beforeCount = UserBadge::where('user_id', Auth::id())->count();
                $badgeService->awardBadgesForUser(auth()->user());
                $afterCount = UserBadge::where('user_id', Auth::id())->count();
                $badgeEarned = $afterCount > $beforeCount;
                \Log::info("Badge earned: {$badgeEarned} (before: {$beforeCount}, after: {$afterCount})");
            } else {
                \Log::info('Challenge already solved by user '.Auth::id());
            }
        }

        return redirect()->route('challenges.show', $challenge)
            ->with('correct', $isCorrect)
            ->with('showBadgeNotification', $badgeEarned);
    }

    /**
     * Challenge creation form
     */
    public function create()
    {
        return Inertia::render('Challenges/Create', [
            'categories' => ChallengeCategory::all(),
        ]);
    }

    /**
     * Store a new challenge + version 1
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:challenges,title',
            'description' => 'required|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'points' => 'required|integer|min:1',
            'category_id' => 'required|exists:challenge_categories,id',
            'flag' => 'required|string',
            'tags' => 'nullable|string',
            'est_time' => 'nullable|integer',
        ]);

        $challenge = Challenge::create([
            'title' => $validated['title'],
        ]);

        ChallengeVersion::create([
            'challenge_id' => $challenge->id,
            'description_md' => $validated['description'],
            'difficulty' => $validated['difficulty'],
            'challenge_category_id' => $validated['category_id'],
            'points' => $validated['points'],
            'flag' => $validated['flag'],
            'author_id' => Auth::id(),
            'tags' => $validated['tags'] ?? null,
            'est_time_to_solve' => $validated['est_time'] ?? null,
            'solution_md' => 'Solution coming soon...',
            // DO NOT manually set version!
            // The model observer assigns the correct version
        ]);

        return redirect()
            ->route('challenges.index')
            ->with('success', 'Challenge created successfully!');
    }
}
