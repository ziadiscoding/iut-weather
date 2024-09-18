<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class OpenWeatherService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.openweathermap.org/data/2.5';

    public function __construct()
    {
        $this->apiKey = config('services.openweather.key');
    }

    public function getWeatherForDate($city, Carbon $date)
    {
        $cacheKey = "weather_{$city}_{$date->format('Y-m-d')}";

        return Cache::remember($cacheKey, 1800, function () use ($city, $date) {
            $endpoint = $date->isFuture() ? 'forecast' : 'weather';
            $response = Http::get("{$this->baseUrl}/{$endpoint}", [
                'q' => $city,
                'appid' => $this->apiKey,
                'units' => 'metric',
                'dt' => $date->timestamp,
            ]);

            $response->throw();  // Throw an exception for non-2xx responses

            $data = $response->json();

            // For forecast data, find the closest time to the requested date
            if ($endpoint === 'forecast') {
                $data = collect($data['list'])->sortBy(function ($item) use ($date) {
                    return abs(Carbon::createFromTimestamp($item['dt'])->diffInSeconds($date));
                })->first();
            }

            return $data;
        });
    }
}