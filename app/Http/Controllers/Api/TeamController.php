<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\DTO\UpdateTeamDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTeamRequest;
use App\Http\Resources\TeamResource;
use App\Services\TeamService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function __construct(
        private TeamService $teamService
    ) {}

    public function show(Request $request): JsonResponse
    {
        $team = $this->teamService->getTeam($request->user()->team);

        return response()->success([
            'team' => TeamResource::make($team),
        ]);
    }

    public function update(UpdateTeamRequest $request): JsonResponse
    {
        $dto = UpdateTeamDTO::from($request->validated());

        $this->teamService->updateTeam($request->user()->team, $dto);

        return response()->success();
    }
}
