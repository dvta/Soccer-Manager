<?php

declare(strict_types=1);

namespace App\DTO;

use Spatie\LaravelData\Data;

class RegisterUserDTO extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {}
}
