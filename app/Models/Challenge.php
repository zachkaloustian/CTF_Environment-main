<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Challenge extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['title'];

    /** Each challenge has many versions */
    public function versions(): HasMany
    {
        return $this->hasMany(ChallengeVersion::class);
    }

    /** Latest published version */
    public function latestVersion(): HasOne
    {
        return $this->hasOne(ChallengeVersion::class)->latestOfMany('version');
    }
}
