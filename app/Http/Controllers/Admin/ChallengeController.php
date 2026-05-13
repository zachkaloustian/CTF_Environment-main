<?php

namespace App\Http\Controllers\Admin;

use App\Models\Challenge;
use App\Models\ChallengeCategory;
use App\Models\ChallengeVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ChallengeController extends \App\Http\Controllers\Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of all challenges (admin view)
     */
    public function index(Request $request)
    {
        $challenges = Challenge::query()
            ->with([
                'versions' => function ($q) {
                    $q->orderBy('version', 'desc')->limit(1);
                },
                'versions.category',
            ])
            ->get()
            ->map(function ($challenge) {
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

        return Inertia::render('Admin/Challenges/Index', [
            'challenges' => $challenges,
        ]);
    }

    /**
     * Show the form for creating a new challenge
     */
    public function create()
    {
        return Inertia::render('Admin/Challenges/Create', [
            'categories' => ChallengeCategory::all(),
        ]);
    }

    /**
     * Store a newly created challenge + version 1
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
            'solution_md' => 'nullable|string',
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
            'solution_md' => $validated['solution_md'] ?? '',
            'tags' => $validated['tags'] ?? null,
            'est_time_to_solve' => $validated['est_time'] ?? null,
            // DO NOT manually set version!
            // The model observer assigns the correct version
        ]);

        return redirect()
            ->route('admin.challenges.index')
            ->with('success', 'Challenge created successfully!');
    }

    /**
     * Display the specified challenge
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

        return Inertia::render('Admin/Challenges/Show', [
            'challenge' => [
                'id' => $challenge->id,
                'title' => $challenge->title,
                'description' => $latest?->description_md ?? '',
                'difficulty' => strtolower($latest?->difficulty?->value ?? 'easy'),
                'points' => $latest?->points ?? 0,
                'category' => $latest?->category?->name ?? 'Uncategorized',
            ],
            'versions' => $versions,
        ]);
    }

    /**
     * Show the form for editing the specified challenge
     */
    public function edit(Challenge $challenge)
    {
        $latest = $challenge->versions()
            ->orderBy('version', 'desc')
            ->first();

        return Inertia::render('Admin/Challenges/Edit', [
            'challenge' => $challenge,
            'latest' => $latest,
            'categories' => ChallengeCategory::all(),
        ]);
    }

    /**
     * Update the specified challenge
     */
    public function update(Request $request, Challenge $challenge)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:challenges,title,'.$challenge->id,
            'description' => 'required|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'points' => 'required|integer|min:1',
            'category_id' => 'required|exists:challenge_categories,id',
            'flag' => 'required|string',
            'solution_md' => 'nullable|string',
            'tags' => 'nullable|string',
            'est_time' => 'nullable|integer',
        ]);

        // Update challenge title if changed
        if ($challenge->title !== $validated['title']) {
            $challenge->update([
                'title' => $validated['title'],
            ]);
        }

        // Create new version
        ChallengeVersion::create([
            'challenge_id' => $challenge->id,
            'description_md' => $validated['description'],
            'difficulty' => $validated['difficulty'],
            'challenge_category_id' => $validated['category_id'],
            'points' => $validated['points'],
            'flag' => $validated['flag'],
            'author_id' => Auth::id(),
            'solution_md' => $validated['solution_md'] ?? '',
            'tags' => $validated['tags'] ?? null,
            'est_time_to_solve' => $validated['est_time'] ?? null,
            // DO NOT manually set version!
            // The model observer assigns the correct version
        ]);

        return redirect()
            ->route('admin.challenges.index')
            ->with('success', 'Challenge updated successfully!');
    }

    /**
     * Remove the specified challenge
     */
    public function destroy(Challenge $challenge)
    {
        $challenge->delete();

        return redirect()
            ->route('admin.challenges.index')
            ->with('success', 'Challenge deleted successfully!');
    }
}
