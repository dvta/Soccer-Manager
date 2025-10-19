<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\TransferController;
use App\Http\Middleware\UserHasTeam;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware(UserHasTeam::class)->group(function () {
        Route::get('/me', [AuthController::class, 'me']);

        Route::get('/team', [TeamController::class, 'show']);
        Route::put('/team', [TeamController::class, 'update']);

        Route::put('/players/{player}', [PlayerController::class, 'update']);
        Route::post('/players/{player}/list-for-transfer', [PlayerController::class, 'listForTransfer']);
        Route::delete('/players/{player}/remove-from-transfer-list', [PlayerController::class, 'removeFromTransferList']);
        Route::get('/transfer-list', [PlayerController::class, 'transferList']);

        Route::post('/transfers/buy', [TransferController::class, 'buyPlayer']);
        Route::get('/transfers/history', [TransferController::class, 'history']);
    });
});
