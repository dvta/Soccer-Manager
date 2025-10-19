<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Number;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property int $country_id
 * @property float $budget
 * @property float $team_value
 * @property \Illuminate\Support\Carbon $created_at
 */
class TeamResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'country_id' => $this->country_id,
            'country' => CountryResource::make($this->whenLoaded('country')),
            'budget' => $this->budget,
            'budget_formatted' => Number::currency((float) $this->budget, 'USD'),
            'team_value' => $this->when($this->resource->relationLoaded('players'), function () {
                return $this->team_value;
            }),
            'team_value_formatted' => $this->when($this->resource->relationLoaded('players'), function () {
                return Number::currency((float) $this->team_value, 'USD');
            }),
            'players' => PlayerResource::collection($this->whenLoaded('players')),
            'user' => UserResource::make($this->whenLoaded('user')),
            'created_at' => $this->created_at,
        ];
    }
}
