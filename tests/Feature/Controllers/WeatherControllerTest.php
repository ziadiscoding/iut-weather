<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('index page loads successfully', function () {
    $response = $this->actingAs($this->user)
        ->get(route('weather.search'));

    $response->assertStatus(200)
        ->assertViewIs('weather.search');
});

test('getCurrentWeather returns weather view with data', function () {
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
            'weather' => [
                ['description' => 'clear sky', 'icon' => '01d']
            ],
            'wind' => ['speed' => 5],
            'clouds' => ['all' => 20],
            'dt' => now()->timestamp,
            'visibility' => 10000,
            'sys' => [
                'sunrise' => now()->subHours(6)->timestamp,
                'sunset' => now()->addHours(6)->timestamp
            ]
        ], 200),
    ]);

    $response = $this->actingAs($this->user)
        ->post(route('weather.current'), [
            'city' => 'Paris',
            'date' => now()->format('Y-m-d'),
        ]);

    $response->assertStatus(200)
        ->assertViewIs('weather.current')
        ->assertViewHas('weather')
        ->assertViewHas('city', 'Paris');
});

test('getCurrentWeather returns error for invalid city', function () {
    Http::fake([
        'api.openweathermap.org/geo/1.0/direct*' => Http::response([], 200),
    ]);

    $response = $this->actingAs($this->user)
        ->post(route('weather.current'), [
            'city' => 'InvalidCity',
            'date' => now()->format('Y-m-d'),
        ]);

    $response->assertRedirect()
        ->assertSessionHas('error');
});