<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Team::with('captain')->withCount('members');

        // Search functionality
        if ($request->has('search') && ! empty($request->search)) {
            $search = $request->search;
            Log::info('Team search', [
                'user_id' => $user->id,
                'search_term' => $search,
            ]);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        // Filter by public teams only
        $query->where('is_public', true);

        try {
            $teams = $query->paginate(20);
        } catch (\Exception $e) {
            Log::error('Teams query failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'search' => $request->search,
            ]);

            return response()->json(['error' => 'Database query failed'], 500);
        }

        Log::info('Teams index loaded', [
            'user_id' => $user->id,
            'teams_count' => $teams->total(),
            'can_create_team' => ! $user->currentTeam() && ! $user->isTeamCaptain(),
            'current_team_id' => $user->currentTeam()?->id,
            'is_team_captain' => $user->isTeamCaptain(),
        ]);

        return Inertia::render('Teams/Index', [
            'teams' => $teams,
            'filters' => $request->only(['search']),
            'canCreateTeam' => ! $user->currentTeam() && ! $user->isTeamCaptain(),
        ]);
    }

    public function create()
    {
        // Check if user can create a team
        if (Auth::user()->currentTeam() || Auth::user()->isTeamCaptain()) {
            return redirect()->route('teams.index')->with('error', 'You are already in a team or are a team captain.');
        }

        return Inertia::render('Teams/Create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Validation
        if ($user->currentTeam() || $user->isTeamCaptain()) {
            return back()->withErrors(['general' => 'You are already in a team or are a team captain.']);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:teams,name'],
            'description' => ['nullable', 'string', 'max:1000'],
            'max_size' => ['required', 'integer', 'min:1', 'max:10'],
            'is_public' => ['boolean'],
        ]);

        // Create team
        $team = Team::create([
            'name' => $validated['name'],
            'captain_id' => $user->id,
            'description' => $validated['description'] ?? null,
            'max_size' => $validated['max_size'],
            'is_public' => $validated['is_public'] ?? true,
        ]);

        // Add captain as first member
        $team->addMember($user);

        return redirect()->route('teams.show', $team)->with('success', 'Team created successfully!');
    }

    public function show(Team $team)
    {
        $team->load(['captain', 'members' => function ($query) {
            $query->orderBy('pivot_joined_at');
        }]);

        $user = Auth::user();
        $isMember = $team->members()->where('user_id', $user->id)->exists();
        $isCaptain = $team->captain_id === $user->id;
        $canJoin = $team->canJoin($user);

        return Inertia::render('Teams/Show', [
            'team' => $team,
            'isMember' => $isMember,
            'isCaptain' => $isCaptain,
            'canJoin' => $canJoin,
            'currentUserTeam' => $user->currentTeam(),
        ]);
    }

    public function join(Request $request)
    {
        $validated = $request->validate([
            'join_code' => ['required', 'string', 'exists:teams,join_code'],
        ]);

        $team = Team::where('join_code', $validated['join_code'])->first();
        $user = Auth::user();

        Log::info('Team join attempt', [
            'user_id' => $user->id,
            'join_code' => $validated['join_code'],
            'team_id' => $team->id,
            'team_name' => $team->name,
        ]);

        if (! $team->canJoin($user)) {
            Log::warning('Team join failed: cannot join', [
                'user_id' => $user->id,
                'team_id' => $team->id,
                'user_current_team' => $user->currentTeam()?->id,
                'is_team_captain' => $user->isTeamCaptain(),
                'team_members_count' => $team->members()->count(),
                'team_max_size' => $team->max_size,
            ]);

            return back()->withErrors(['join_code' => 'Cannot join this team. You may already be in a team or the team may be full.']);
        }

        $team->addMember($user);

        Log::info('Team join successful', [
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);

        return redirect()->route('teams.show', $team)->with('success', 'Successfully joined the team!');
    }

    public function leave(Team $team)
    {
        $user = Auth::user();

        // Check if user is actually a member
        if (! $team->members()->where('user_id', $user->id)->exists()) {
            return back()->withErrors(['general' => 'You are not a member of this team.']);
        }

        // Captain cannot leave - must transfer captaincy first
        if ($team->captain_id === $user->id) {
            return back()->withErrors(['general' => 'Team captains cannot leave their team. Transfer captaincy to another member first.']);
        }

        $team->removeMember($user);

        return redirect()->route('teams.index')->with('success', 'Successfully left the team.');
    }

    public function manage(Team $team)
    {
        Gate::authorize('manage', $team);

        $team->load(['captain', 'members' => function ($query) {
            $query->orderBy('pivot_joined_at');
        }]);

        return Inertia::render('Teams/Manage', [
            'team' => $team,
        ]);
    }

    public function update(Request $request, Team $team)
    {
        Gate::authorize('manage', $team);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('teams')->ignore($team->id)],
            'description' => ['nullable', 'string', 'max:1000'],
            'max_size' => ['required', 'integer', 'min:'.$team->members()->count(), 'max:10'],
            'is_public' => ['boolean'],
        ]);

        $team->update($validated);

        return back()->with('success', 'Team updated successfully!');
    }

    public function invite(Request $request, Team $team)
    {
        Gate::authorize('manage', $team);

        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $team->canJoin($user)) {
            return back()->withErrors(['email' => 'This user cannot join the team.']);
        }

        $team->addMember($user);

        return back()->with('success', 'User invited to the team successfully!');
    }

    public function removeMember(Request $request, Team $team, User $member)
    {
        Gate::authorize('manage', $team);

        if ($member->id === $team->captain_id) {
            return back()->withErrors(['member' => 'Cannot remove the team captain.']);
        }

        if (! $team->members()->where('user_id', $member->id)->exists()) {
            return back()->withErrors(['member' => 'User is not a member of this team.']);
        }

        $team->removeMember($member);

        return back()->with('success', 'Member removed from the team.');
    }

    public function transferCaptain(Request $request, Team $team, User $newCaptain)
    {
        Gate::authorize('manage', $team);

        if (! $team->members()->where('user_id', $newCaptain->id)->exists()) {
            return back()->withErrors(['captain' => 'New captain must be a current team member.']);
        }

        $team->transferCaptainship($newCaptain);

        return back()->with('success', 'Team captain transferred successfully!');
    }

    public function disband(Team $team)
    {
        Gate::authorize('manage', $team);

        // Remove all members
        $team->members()->detach();

        // Delete the team
        $team->delete();

        return redirect()->route('teams.index')->with('success', 'Team disbanded successfully.');
    }
}
