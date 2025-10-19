<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('success', function (?array $data = null): JsonResponse {
            $response = [
                'success' => true,
            ];

            if ($data !== null) {
                $response = array_merge($response, $data);
            }

            return Response::json($response);
        });

        Response::macro('error', function (string $message, int $status = 400): JsonResponse {
            return Response::json([
                'success' => false,
                'errorMessage' => $message,
            ], $status);
        });
    }
}
