<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Player;
use App\Models\Team;
use App\Models\Transfer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class TransferService
{
    private const MIN_VALUE_INCREASE_PERCENTAGE = 10;

    private const MAX_VALUE_INCREASE_PERCENTAGE = 100;

    public function buyPlayer(Player $player, Team $buyerTeam): Transfer
    {
        if (! $player->is_on_transfer_list) {
            throw new InvalidArgumentException(__('transfers.not_on_list'));
        }

        if ($player->team_id === $buyerTeam->id) {
            throw new InvalidArgumentException(__('transfers.own_player'));
        }

        if ($buyerTeam->budget < $player->asking_price) {
            throw new InvalidArgumentException(__('transfers.insufficient_funds'));
        }

        return DB::transaction(function () use ($player, $buyerTeam): Transfer {
            $sellerTeam = $player->team;
            if (! $sellerTeam) {
                throw new InvalidArgumentException(__('transfers.no_team'));
            }

            $oldValue = (float) $player->market_value;
            $transferPrice = (float) $player->asking_price;

            $newValue = $this->calculateNewMarketValue($oldValue);

            $sellerTeam->increment('budget', $transferPrice);
            $buyerTeam->decrement('budget', $transferPrice);

            $transfer = Transfer::create([
                'player_id' => $player->id,
                'from_team_id' => $sellerTeam->id,
                'to_team_id' => $buyerTeam->id,
                'transfer_price' => $transferPrice,
                'old_market_value' => $oldValue,
                'new_market_value' => $newValue,
            ]);

            $player->update([
                'team_id' => $buyerTeam->id,
                'market_value' => $newValue,
                'is_on_transfer_list' => false,
                'asking_price' => null,
            ]);

            return $transfer->load(['player', 'fromTeam', 'toTeam']);
        });
    }

    private function calculateNewMarketValue(float $currentValue): float
    {
        $increasePercentage = rand(
            self::MIN_VALUE_INCREASE_PERCENTAGE,
            self::MAX_VALUE_INCREASE_PERCENTAGE
        );

        return $currentValue * (1 + ($increasePercentage / 100));
    }

    public function getTeamTransferHistory(Team $team): LengthAwarePaginator
    {
        return Transfer::where('from_team_id', $team->id)
            ->orWhere('to_team_id', $team->id)
            ->with(['player', 'fromTeam', 'toTeam'])
            ->orderBy('created_at', 'desc')
            ->paginate();
    }
}
