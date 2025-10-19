<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\DTO\ListPlayerForTransferDTO;
use App\DTO\UpdatePlayerDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\ListPlayerForTransferRequest;
use App\Http\Requests\UpdatePlayerRequest;
use App\Http\Resources\PlayerResource;
use App\Models\Player;
use App\Services\PlayerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function __construct(
        private PlayerService $playerService
    ) {}

    public function update(UpdatePlayerRequest $request, Player $player): JsonResponse
    {
        $this->authorize('update', $player);

        $dto = UpdatePlayerDTO::from($request->validated());

        $this->playerService->updatePlayer($player, $dto);

        return response()->success();
    }

    public function listForTransfer(ListPlayerForTransferRequest $request, Player $player): JsonResponse
    {
        $this->authorize('listForTransfer', $player);

        $dto = ListPlayerForTransferDTO::from($request->validated());

        $this->playerService->listPlayerForTransfer($player, $dto->asking_price);

        return response()->success();
    }

    public function removeFromTransferList(Request $request, Player $player): JsonResponse
    {
        $this->authorize('removeFromTransferList', $player);

        $this->playerService->removePlayerFromTransferList($player);

        return response()->success();
    }

    public function transferList(): JsonResponse
    {
        $players = $this->playerService->getPlayersOnTransferList();

        return response()->success([
            'players' => PlayerResource::collection($players),
        ]);
    }
}
