<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\DTO\LoginUserDTO;
use App\DTO\RegisterUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * Register a new user.
     */
    public function register(RegisterRequest $dto): JsonResponse
    {
        $user = $this->authService->register(RegisterUserDTO::from($dto->validated()));

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->success([
            'token' => $token,
        ]);
    }

    /**
     * Login user.
     */
    public function login(LoginUserDTO $dto): JsonResponse
    {
        $result = $this->authService->login($dto);

        return response()->success([
            'token' => $result['token'],
        ]);
    }

    /**
     * Logout user.
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->success();
    }

    /**
     * Get authenticated user.
     */
    public function me(Request $request): JsonResponse
    {
        return response()->success([
            'user' => UserResource::make($request->user()->load('team.players')),
        ]);
    }
}
