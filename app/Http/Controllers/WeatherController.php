<?php

namespace App\Http\Controllers;

use App\Services\OpenWeatherService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(OpenWeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function index(Request $request)
    {
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();
        $dates = $this->getDateRange($date);
        return view('weather.search', compact('dates', 'date'));
    }

    public function getCurrentWeather(Request $request)
    {
        $request->validate([
            'city' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $date = Carbon::parse($request->date);
        $dates = $this->getDateRange($date);

        try {
            $weather = $this->weatherService->getWeatherForDate($request->city, $date);
            return view('weather.current', [
                'weather' => $weather,
                'city' => $request->city,
                'date' => $date,
                'dates' => $dates
            ]);
        } catch (\Exception $e) {
            return back()->withError('Unable to fetch weather data. Please try again.');
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