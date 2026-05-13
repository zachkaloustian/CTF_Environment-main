<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'captain_id',
        'max_size',
        'join_code',
        'logo',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'max_size' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($team) {
            if (empty($team->join_code)) {
                $team->join_code = Str::random(8);
            }
        });
    }

    public function captain(): BelongsTo
    {
        return $this->belongsTo(User::class, 'captain_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('joined_at')->withTimestamps();
    }

    public function isFull(): bool
    {
        return $this->members()->count() >= $this->max_size;
    }

    public function canJoin(User $user): bool
    {
        // User already in this team
        if ($this->members()->where('user_id', $user->id)->exists()) {
            return false;
        }

        // User already in another team
        if ($user->team()->exists()) {
            return false;
        }

        // Team is full
        if ($this->isFull()) {
            return false;
        }

        return true;
    }

    public function addMember(User $user): bool
    {
        if (! $this->canJoin($user)) {
            return false;
        }

        $this->members()->attach($user->id, ['joined_at' => now()]);

        return true;
    }

    public function removeMember(User $user): bool
    {
        if ($user->id === $this->captain_id) {
            return false; // Can't remove captain
        }

        $this->members()->detach($user->id);

        return true;
    }

    public function transferCaptainship(User $newCaptain): bool
    {
        if (! $this->members()->where('user_id', $newCaptain->id)->exists()) {
            return false; // New captain must be a member
        }

        $this->captain_id = $newCaptain->id;
        $this->save();

        return true;
    }
}
