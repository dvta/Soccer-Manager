<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Number;

/**
 * @property int $id
 * @property int $team_id
 * @property string $first_name
 * @property string $last_name
 * @property int $age
 * @property \App\Enums\PlayerPosition $position
 * @property float $market_value
 * @property bool $is_on_transfer_list
 * @property float|null $asking_price
 */
class PlayerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'team_id' => $this->team_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->first_name.' '.$this->last_name,
            'country' => CountryResource::make($this->whenLoaded('country')),
            'age' => $this->age,
            'position' => $this->position->value,
            'market_value' => $this->market_value,
            'market_value_formatted' => Number::currency((float) $this->market_value, 'USD'),
            'is_on_transfer_list' => $this->is_on_transfer_list,
            'asking_price' => $this->asking_price,
            'asking_price_formatted' => $this->asking_price ? Number::currency((float) $this->asking_price, 'USD') : null,
            'team' => TeamResource::make($this->whenLoaded('team')),
        ];
    }
}
