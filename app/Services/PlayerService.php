<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\UpdatePlayerDTO;
use App\Models\Player;
use Illuminate\Database\Eloquent\Collection;

class PlayerService
{
    public function updatePlayer(Player $player, UpdatePlayerDTO $dto): Player
    {
        $player->update($dto->toArray());

        return $player->fresh();
    }

    public function listPlayerForTransfer(Player $player, float $askingPrice): Player
    {
        $player->listForTransfer($askingPrice);

        return $player->fresh();
    }

    public function removePlayerFromTransferList(Player $player): Player
    {
        $player->removeFromTransferList();

        return $player->fresh();
    }

    public function getPlayersOnTransferList(): Collection
    {
        return Player::where('is_on_transfer_list', true)
            ->with([
                'team.user',
                'team.country',
                'country',
            ])
            ->get();
    }
}
