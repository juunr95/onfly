<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\TravelsController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(JwtMiddleware::class)->group(function () {
    Route::apiResource('orders', OrdersController::class);
    Route::apiResource('travels', TravelsController::class);

    Route::post('travels/{travel}/cancel', [TravelsController::class, 'cancelTravel']);
    Route::post('travels/{travel}/approve', [TravelsController::class, 'approveTravel']);
});

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
