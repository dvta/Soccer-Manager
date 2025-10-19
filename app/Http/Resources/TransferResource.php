<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Number;

/**
 * @property int $id
 * @property int $player_id
 * @property int $from_team_id
 * @property int $to_team_id
 * @property float $transfer_price
 * @property float $old_market_value
 * @property float $new_market_value
 * @property \Illuminate\Support\Carbon $created_at
 */
class TransferResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'player_id' => $this->player_id,
            'from_team_id' => $this->from_team_id,
            'to_team_id' => $this->to_team_id,
            'transfer_price' => $this->transfer_price,
            'transfer_price_formatted' => Number::currency((float) $this->transfer_price, 'USD'),
            'old_market_value' => $this->old_market_value,
            'old_market_value_formatted' => Number::currency((float) $this->old_market_value, 'USD'),
            'new_market_value' => $this->new_market_value,
            'new_market_value_formatted' => Number::currency((float) $this->new_market_value, 'USD'),
            'player' => PlayerResource::make($this->whenLoaded('player')),
            'from_team' => TeamResource::make($this->whenLoaded('fromTeam')),
            'to_team' => TeamResource::make($this->whenLoaded('toTeam')),
            'created_at' => $this->created_at,
        ];
    }
}
