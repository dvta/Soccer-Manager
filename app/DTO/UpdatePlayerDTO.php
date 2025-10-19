<?php

declare(strict_types=1);

namespace App\DTO;

use Spatie\LaravelData\Data;

class UpdatePlayerDTO extends Data
{
    public function __construct(
        public ?string $first_name = null,
        public ?string $last_name = null,
        public ?int $country_id = null,
    ) {}

    /**
     * @return array<string, string|int>
     */
    public function toArray(): array
    {
        return array_filter([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'country_id' => $this->country_id,
        ], fn ($value) => $value !== null);
    }
}
