<?php

namespace App\Http\Controllers;

use App\Services\OpenWeatherService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(OpenWeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function index()
    {
        return view('weather.search');
    }

    public function getCurrentWeather(Request $request)
    {
        $request->validate([
            'city' => 'required|string|max:255',
        ]);

        try {
            $weather = $this->weatherService->getCurrentWeather($request->city);
            return view('weather.current', ['weather' => $weather, 'city' => $request->city]);
        } catch (\Exception $e) {
            return back()->withError('Unable to fetch weather data. Please try again.');
        }
    }
}