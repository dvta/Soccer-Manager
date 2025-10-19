<?php

declare(strict_types=1);

namespace App\DTO;

use Spatie\LaravelData\Data;

class ListPlayerForTransferDTO extends Data
{
    public function __construct(
        public float $asking_price,
    ) {}
}
