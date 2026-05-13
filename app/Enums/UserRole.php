<?php

namespace App\Enums;

// User roles for the CTF platform
enum UserRole: string
{
    case Player = 'player';
    case TeamCaptain = 'team_captain';
    case Proctor = 'proctor';
    case Instructor = 'instructor';
    case Admin = 'admin';

    // Returns display name for the role
    public function label(): string
    {
        return match($this) {
            self::Player => 'Player',
            self::TeamCaptain => 'Team Captain',
            self::Proctor => 'Proctor',
            self::Instructor => 'Instructor',
            self::Admin => 'Admin',
        };
    }

    // Returns description for the role
    public function description(): string
    {
        return match($this) {
            self::Player => 'Standard participant - Solve challenges, view leaderboards, edit profile',
            self::TeamCaptain => 'Team manager - Invite/remove members, rename team, manage settings',
            self::Proctor => 'Read-only monitor - Observe submissions and logs without editing',
            self::Instructor => 'Faculty administrator - Create/edit challenges, schedule events, adjust scores',
            self::Admin => 'System administrator - Manage global settings, users, roles, and infrastructure',
        };
    }
}
