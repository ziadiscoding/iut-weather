<?php

namespace App\Http\Controllers;

use App\Services\OpenWeatherService;
use App\Http\Requests\WeatherFormRequest;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(OpenWeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function index()
    {
        $date = Carbon::today();
        $dates = $this->getDateRange($date);
        return view('weather.search', compact('dates', 'date'));
    }

    public function getCurrentWeather(WeatherFormRequest $request)
    {
        $city = $request->validated()['city'];
        $date = Carbon::parse($request->input('date', now()));
        $dates = $this->getDateRange($date);

        try {
            $weatherData = $this->weatherService->getWeatherForDate($city, $date);

            // Check if we're displaying a forecast or current weather
            $isForecast = $date->isFuture() && $date->diffInDays(now()) <= 5;

            return view('weather.current', [
                'weather' => $weatherData,
                'city' => $city,
                'date' => $date,
                'dates' => $dates,
                'isForecast' => $isForecast,
                'coordinates' => $weatherData['coordinates'] ?? null
            ]);
        } catch (\Exception $e) {
            Log::error("Error fetching weather data: " . $e->getMessage());
            return back()->withError('Unable to fetch weather data. Please try again. Error: ' . $e->getMessage());
        }
    }

    private function getDateRange(Carbon $centerDate)
    {
        return collect(range(-3, 3))->map(function ($offset) use ($centerDate) {
            $date = $centerDate->copy()->addDays($offset);
            return [
                'date' => $date,
                'formatted' => $date->format('Y-m-d'),
                'label' => $this->getDateLabel($date),
            ];
        });
    }

    private function getDateLabel(Carbon $date)
    {
        $today = Carbon::today();
        if ($date->isSameDay($today)) {
            return 'Today';
        } elseif ($date->isSameDay($today->copy()->subDay())) {
            return 'Yesterday';
        } elseif ($date->isSameDay($today->copy()->addDay())) {
            return 'Tomorrow';
        } else {
            return $date->format('D, M j');
        }
    }
}