<?php

declare(strict_types=1);

namespace App\Enums;

enum PlayerPosition: string
{
    case GOALKEEPER = 'goalkeeper';
    case DEFENDER = 'defender';
    case MIDFIELDER = 'midfielder';
    case ATTACKER = 'attacker';

    public function label(): string
    {
        return match ($this) {
            self::GOALKEEPER => 'Goalkeeper',
            self::DEFENDER => 'Defender',
            self::MIDFIELDER => 'Midfielder',
            self::ATTACKER => 'Attacker',
        };
    }
}
