<?php

declare(strict_types=1);

namespace App\DTO;

use Spatie\LaravelData\Data;

class LoginUserDTO extends Data
{
    public function __construct(
        public string $email,
        public string $password,
    ) {}
}
