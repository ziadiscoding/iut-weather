<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\WeatherForecastNotification;
use App\Services\OpenWeatherService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SendWeatherForecastsCommand extends Command
{
    protected $signature = 'weather:send-forecasts';
    protected $description = 'Send weather forecasts to users';

    public function handle(OpenWeatherService $weatherService)
{
    $users = User::whereHas('cities', function ($query) {
        $query->where('send_forecast', true);
    })->get();

    foreach ($users as $user) {
        try {
            $forecasts = [];
            foreach ($user->cities()->where('send_forecast', true)->get() as $city) {
                $weatherData = $weatherService->getWeatherForDate($city->city, Carbon::now());
                $forecasts[$city->city] = $this->formatForecast($weatherData);
            }

            $csvPath = $this->generateCsv($forecasts);
            $user->notify(new WeatherForecastNotification($csvPath));
        } catch (\Exception $e) {
            $this->error("Failed to send forecast to user {$user->id}: " . $e->getMessage());
            \Log::error("Failed to send forecast to user {$user->id}: " . $e->getMessage());
        }
    }

    $this->info('Weather forecasts sending process completed.');
}

    private function formatForecast($weatherData)
    {
        return [
            'date' => Carbon::createFromTimestamp($weatherData['dt'])->format('Y-m-d'),
            'temp' => $weatherData['main']['temp'],
            'description' => $weatherData['weather'][0]['description'],
        ];
    }

    private function generateCsv($forecasts)
    {
        $csv = "City,Date,Temperature,Description\n";
        foreach ($forecasts as $city => $forecast) {
            $csv .= "{$city},{$forecast['date']},{$forecast['temp']},{$forecast['description']}\n";
        }

        $path = 'forecasts/' . uniqid() . '.csv';
        Storage::put($path, $csv);
        return $path;
    }
}