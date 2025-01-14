<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeatherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'temperature' => $this['main']['temp'],
            'feels_like' => $this['main']['feels_like'],
            'humidity' => $this['main']['humidity'],
            'pressure' => $this['main']['pressure'],
            'description' => $this['weather'][0]['description'],
            'wind_speed' => $this['wind']['speed'],
            'clouds' => $this['clouds']['all'],
            'timestamp' => $this['dt'],
            'coordinates' => [
                'lat' => $this['coordinates']['lat'] ?? null,
                'lon' => $this['coordinates']['lon'] ?? null,
            ],
        ];
    }
}

