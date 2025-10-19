<?php

declare(strict_types=1);

namespace App\DTO;

use Spatie\LaravelData\Data;

class TransferPlayerDTO extends Data
{
    public function __construct(
        public int $player_id,
        public float $asking_price,
    ) {}
}
