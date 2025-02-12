<?php

use App\Services\OpenWeatherService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    $this->service = new OpenWeatherService();
    Cache::flush();
});

test('getWeatherForDate returns weather data for current date', function () {
    Http::fake([
        'api.openweathermap.org/geo/1.0/direct*' => Http::response([
            [
                'lat' => 48.8566,
                'lon' => 2.3522,
            ]
        ], 200),
        'api.openweathermap.org/data/2.5/weather*' => Http::response([
            'main' => [
                'temp' => 20,
                'feels_like' => 19,
                'humidity' => 65,
                'pressure' => 1015
            ],
            'weather' => [
                ['description' => 'clear sky']
            ],
            'wind' => ['speed' => 5],
            'clouds' => ['all' => 20],
            'dt' => now()->timestamp,
            'coordinates' => ['lat' => 48.8566, 'lon' => 2.3522],
        ], 200),
    ]);

    $weather = $this->service->getWeatherForDate('Paris', now());

    expect($weather)
        ->toHaveKey('main')
        ->and($weather['main'])
        ->toHaveKey('temp')
        ->toHaveKey('feels_like')
        ->and($weather['coordinates'])
        ->toBe(['lat' => 48.8566, 'lon' => 2.3522]);
});

test('getWeatherForDate caches responses', function () {
    Http::fake([
        'api.openweathermap.org/geo/1.0/direct*' => Http::response([
            ['lat' => 48.8566, 'lon' => 2.3522]
        ], 200),
        'api.openweathermap.org/data/2.5/weather*' => Http::response([
            'main' => [
                'temp' => 20,
                'feels_like' => 19,
                'humidity' => 65,
                'pressure' => 1015
            ],
            'weather' => [['description' => 'clear sky']],
            'wind' => ['speed' => 5],
            'clouds' => ['all' => 20],
            'dt' => now()->timestamp,
        ], 200),
    ]);

    $this->service->getWeatherForDate('Paris', now());
    $this->service->getWeatherForDate('Paris', now());

    Http::assertSentCount(2); // One for geo, one for weather
});

test('getWeatherForDate throws exception for invalid city', function () {
    Http::fake([
        'api.openweathermap.org/geo/1.0/direct*' => Http::response([], 200),
    ]);

    $this->service->getWeatherForDate('InvalidCity', now());
})->throws(\Exception::class, 'Unable to find coordinates for the city.');

