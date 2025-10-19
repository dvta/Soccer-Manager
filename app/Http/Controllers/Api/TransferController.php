<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BuyPlayerRequest;
use App\Models\Player;
use App\Services\TransferService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function __construct(
        private TransferService $transferService
    ) {}

    /**
     * Buy a player from transfer market.
     */
    public function buyPlayer(BuyPlayerRequest $request): JsonResponse
    {
        $player = Player::with('team')->findOrFail($request->validated('player_id'));
        $team = $request->user()->team;

        try {
            $transfer = $this->transferService->buyPlayer($player, $team);

            return response()->success();
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function history(Request $request): JsonResponse
    {
        $team = $request->user()->team;

        $transfers = $this->transferService->getTeamTransferHistory($team);

        return response()->json([
            'transfers' => $transfers,
        ]);
    }
}
