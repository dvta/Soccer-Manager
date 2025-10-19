<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $player_id
 * @property int $from_team_id
 * @property int $to_team_id
 * @property float $transfer_price
 * @property float $old_market_value
 * @property float $new_market_value
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read Player $player
 * @property-read Team $fromTeam
 * @property-read Team $toTeam
 */
class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'from_team_id',
        'to_team_id',
        'transfer_price',
        'old_market_value',
        'new_market_value',
    ];

    protected $casts = [
        'transfer_price' => 'decimal:2',
        'old_market_value' => 'decimal:2',
        'new_market_value' => 'decimal:2',
    ];

    /**
     * Get the player that was transferred.
     *
     * @return BelongsTo<Player, $this>
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    /**
     * Get the team the player was transferred from.
     *
     * @return BelongsTo<Team, $this>
     */
    public function fromTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'from_team_id');
    }

    /**
     * Get the team the player was transferred to.
     *
     * @return BelongsTo<Team, $this>
     */
    public function toTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'to_team_id');
    }
}
