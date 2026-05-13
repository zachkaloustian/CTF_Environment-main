<?php

namespace App\Models;

use App\Enums\BadgeType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'type',
        'criteria',
    ];

    protected function casts(): array
    {
        return [
            'type' => BadgeType::class,
            'criteria' => 'array',
        ];
    }

    public function userBadges(): HasMany
    {
        return $this->hasMany(UserBadge::class);
    }
}
