<?php

namespace App\Models;

use App\Services\BadgeAwardService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChallengeSolve extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'challenge_id',
        'challenge_title',
        'points',
    ];

    // protected static function booted()
    // {
    //     static::created(function (self $solve) {
    //         $user = User::query()->find($solve->user_id);

    //         if ($user) {
    //             app(BadgeAwardService::class)->awardBadgesForUser($user);
    //         }
    //     });
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
