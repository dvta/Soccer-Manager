<?php

declare(strict_types=1);

namespace App\DTO;

use Spatie\LaravelData\Data;

class UpdateTeamDTO extends Data
{
    public function __construct(
        public ?string $name = null,
        public ?int $country_id = null,
    ) {}

    /**
     * @return array<string, string|int>
     */
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'country_id' => $this->country_id,
        ], fn ($value) => $value !== null);
    }
}
