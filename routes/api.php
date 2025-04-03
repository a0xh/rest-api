<?php declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Controllers\Api\V1\AuthController;

Route::prefix('v1')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
    });
});
