<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WeatherApiController;
use App\Http\Controllers\Api\UserCityApiController;
use App\Http\Controllers\Api\AuthController;

Route::post('/auth/token', [AuthController::class, 'token']);

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    
    Route::get('/weather', [WeatherApiController::class, 'current']);
    Route::get('/forecast', [WeatherApiController::class, 'forecast']);
    
    Route::prefix('users/places')->group(function () {
        Route::get('/', [UserCityApiController::class, 'index']);
        Route::post('/', [UserCityApiController::class, 'store']);
        Route::patch('/{place}/send-forecast', [UserCityApiController::class, 'toggleForecast']);
        Route::patch('/{place}/favorite', [UserCityApiController::class, 'toggleFavorite']);
        Route::patch('/{place}', [UserCityApiController::class, 'destroy']);
    });
});
