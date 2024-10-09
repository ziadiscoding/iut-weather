<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class OpenWeatherService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.openweathermap.org/data/2.5';
    protected $geoUrl = 'https://api.openweathermap.org/geo/1.0';

    public function __construct()
    {
        $this->apiKey = config('services.openweather.key');
    }

    public function getWeatherForDate($city, Carbon $date)
    {
        $coordinates = $this->getCoordinates($city);

        if (!$coordinates) {
            throw new \Exception("Unable to find coordinates for the city.");
        }

        $cacheKey = "weather_{$city}_{$date->format('Y-m-d')}";

        return Cache::remember($cacheKey, 1800, function () use ($coordinates, $date) {
            $endpoint = $date->isFuture() ? 'forecast' : 'weather';
            $response = Http::get("{$this->baseUrl}/{$endpoint}", [
                'lat' => $coordinates['lat'],
                'lon' => $coordinates['lon'],
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

            $data['coordinates'] = $coordinates;  // Add coordinates to the response

            return $data;
        });
    }

    protected function getCoordinates($city)
    {
        $cacheKey = "coordinates_{$city}";

        return Cache::remember($cacheKey, 86400, function () use ($city) {
            Log::info("Fetching coordinates for {$city}");
            $response = Http::get("{$this->geoUrl}/direct", [
                'q' => $city,
                'limit' => 1,
                'appid' => $this->apiKey,
            ]);

            if ($response->failed()) {
                Log::error("Failed to fetch coordinates for {$city}: " . $response->body());
                return null;
            }

            $data = $response->json();
            
            if (empty($data)) {
                return null;
            }

            return [
                'lat' => $data[0]['lat'],
                'lon' => $data[0]['lon'],
            ];
        });
    }
}