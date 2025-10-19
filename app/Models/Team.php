<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property int $country_id
 * @property float $budget
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read User $user
 * @property-read Country $country
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Player> $players
 * @property-read float $team_value
 */
class Team extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'user_id',
        'name',
        'country_id',
        'budget',
    ];

    /** @var array<int, string> */
    public array $translatable = [
        'name',
    ];

    protected $casts = [
        'budget' => 'decimal:2',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Country, $this>
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return HasMany<Player, $this>
     */
    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    public function getTeamValueAttribute(): float
    {
        return (float) $this->players()->sum('market_value');
    }
}
