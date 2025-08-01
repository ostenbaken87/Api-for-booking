<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\ResourceController;

Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
        });
    });

    Route::prefix('resources')->group(function () {
        Route::get('/', [ResourceController::class, 'index']);
        Route::get('/resource/{resourceId}', [ResourceController::class, 'show']);

        Route::middleware(['auth:sanctum', 'admin'])->group(function () {
            Route::post('/resource', [ResourceController::class, 'store']);
            Route::patch('/resource/{resourceId}', [ResourceController::class, 'update']);
            Route::delete('/resource/{resourceId}', [ResourceController::class, 'destroy']);
        });
    });

    Route::prefix('bookings')->group(function(){
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::get('/',[BookingController::class,'index']);
            Route::post('/booking',[BookingController::class,'store']);
        });
    });
});
