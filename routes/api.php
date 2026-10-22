<?php

use App\Http\Controllers\Api\StatsController;
use App\Http\Controllers\Api\TokenController;
use Illuminate\Support\Facades\Route;

Route::post('/tokens', [TokenController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/stats/monthly', [StatsController::class, 'monthly']);
    Route::get('/stats/weekly', [StatsController::class, 'weekly']);
});
