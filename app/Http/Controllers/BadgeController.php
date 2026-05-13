<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class BadgeController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $badges = $user->badges->map(function ($badge) {
            return [
                'id' => $badge->id,
                'name' => $badge->name,
                'description' => $badge->description,
                'icon' => $badge->icon,
                'awarded_at' => $badge->pivot->awarded_at,
            ];
        });

        return Inertia::render('Badges/Index', [
            'badges' => $badges,
        ]);
    }
}
