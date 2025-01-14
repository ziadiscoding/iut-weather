<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\OpenWeatherService;

class ShowCurrentWeatherCommand extends Command
{
    protected $signature = 'weather:show {city : The city name to get weather for}';
    protected $description = 'Show current weather for a specified city';

    protected $weatherService;

    public function __construct(OpenWeatherService $weatherService)
    {
        parent::__construct();
        $this->weatherService = $weatherService;
    }

    public function handle()
    {
        $city = $this->argument('city');

        try {
            $weather = $this->weatherService->getWeatherForDate($city, now());

            $this->info("Current weather in {$city}:");
            $this->newLine();
            $this->table(
                ['Metric', 'Value'],
                [
                    ['Temperature', $weather['main']['temp'] . '°C'],
                    ['Feels Like', $weather['main']['feels_like'] . '°C'],
                    ['Humidity', $weather['main']['humidity'] . '%'],
                    ['Pressure', $weather['main']['pressure'] . ' hPa'],
                    ['Weather', $weather['weather'][0]['description']],
                    ['Wind Speed', $weather['wind']['speed'] . ' m/s'],
                    ['Cloudiness', $weather['clouds']['all'] . '%'],
                ]
            );

        } catch (\Exception $e) {
            $this->error("Failed to fetch weather data for {$city}");
            $this->error($e->getMessage());
            return 1;
        }

        return 0;
    }
}