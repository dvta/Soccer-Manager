<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PlayerPosition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property int $team_id
 * @property string $first_name
 * @property string $last_name
 * @property int $country_id
 * @property int $age
 * @property PlayerPosition $position
 * @property float $market_value
 * @property bool $is_on_transfer_list
 * @property float|null $asking_price
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read Team $team
 * @property-read Country $country
 */
class Player extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'team_id',
        'first_name',
        'last_name',
        'country_id',
        'age',
        'position',
        'market_value',
        'is_on_transfer_list',
        'asking_price',
    ];

    /** @var array<int, string> */
    public array $translatable = [
        'first_name',
        'last_name',
    ];

    protected $casts = [
        'market_value' => 'decimal:2',
        'asking_price' => 'decimal:2',
        'is_on_transfer_list' => 'boolean',
        'age' => 'integer',
        'position' => PlayerPosition::class,
    ];

    /**
     * @return BelongsTo<Team, $this>
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @return BelongsTo<Country, $this>
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return HasMany<Transfer, $this>
     */
    public function transfers(): HasMany
    {
        return $this->hasMany(Transfer::class);
    }

    public function listForTransfer(float $askingPrice): void
    {
        $this->update([
            'is_on_transfer_list' => true,
            'asking_price' => $askingPrice,
        ]);
    }

    public function removeFromTransferList(): void
    {
        $this->update([
            'is_on_transfer_list' => false,
            'asking_price' => null,
        ]);
    }
}
