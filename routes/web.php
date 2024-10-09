<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserCityController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/weather', [WeatherController::class, 'index'])->name('weather.search');
    Route::post('/weather', [WeatherController::class, 'getCurrentWeather'])->name('weather.current');
    Route::get('/my-cities', [UserCityController::class, 'index'])->name('user_cities.index');
    Route::post('/my-cities', [UserCityController::class, 'store'])->name('user_cities.store');
    Route::delete('/my-cities/{userCity}', [UserCityController::class, 'destroy'])->name('user_cities.destroy');
    Route::patch('/my-cities/{userCity}/toggle-favorite', [UserCityController::class, 'toggleFavorite'])->name('user_cities.toggle_favorite');
    Route::patch('/my-cities/{userCity}/toggle-forecast', [UserCityController::class, 'toggleForecast'])->name('user_cities.toggle_forecast');
});

require __DIR__.'/auth.php';

