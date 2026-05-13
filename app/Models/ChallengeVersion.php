<?php


namespace App\Models;
use App\Enums\ChallengeDifficulty;
use App\Observers\ChallengeVersionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Register observer to handle events
#[ObservedBy([ChallengeVersionObserver::class])]
class ChallengeVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'challenge_id',
        'description_md',
        'difficulty',
        'challenge_category_id',
        'points',
        'flag',
        'max_attempts',
        'author_id',
        'tags',
        'est_time_to_solve',
        'solution_md'
        // DO NOT add 'version' — it must be auto-calculated
    ];

    protected function casts(): array
    {
        return [
            'difficulty' => ChallengeDifficulty::class,
        ];
    }

    /**
     * Automatically assign next version number
     */
    protected static function booted()
    {
        static::creating(function ($version) {
            // Get highest existing version number for this challenge
            $max = self::where('challenge_id', $version->challenge_id)->max('version');

            // New version = max + 1 (or 1 if none exist)
            $version->version = ($max ?? 0) + 1;
        });
    }

    /**
     * Parent Challenge
     */
    public function challenge(): BelongsTo
    {
        return $this->belongsTo(Challenge::class);
    }

    /**
     * Category (ex: Web, Crypto, Forensics)
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ChallengeCategory::class, 'challenge_category_id');
    }

    /**
     * Author user
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

