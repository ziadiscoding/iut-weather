<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->token = $this->user->createToken('test')->plainTextToken;
});

test('api returns current weather data', function () {
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

    $response = $this->withToken($this->token)
        ->get('/api/v1/weather?place=Paris');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'temperature',
                'feels_like',
                'humidity',
                'description',
                'wind_speed',
                'clouds',
                'timestamp'
            ]
        ]);
});

test('api requires authentication', function () {
    $response = $this->getJson('/api/v1/weather?place=Paris');
    $response->assertStatus(401);
});

test('api returns error for invalid city', function () {
    Http::fake([
        'api.openweathermap.org/geo/1.0/direct*' => Http::response([], 200),
    ]);

    $response = $this->withToken($this->token)
        ->getJson('/api/v1/weather?place=InvalidCity');

    $response->assertStatus(404)
        ->assertJson(['message' => 'Unable to find coordinates for the city.']);
});

test('api returns error for missing parameters', function () {
    $response = $this->withToken($this->token)
        ->getJson('/api/v1/weather');

    $response->assertStatus(404)
        ->assertJson(['message' => 'The place field is required.']);
});