<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserCity;
use App\Services\OpenWeatherService;
use App\Http\Resources\WeatherResource;
use App\Http\Resources\UserCityResource;
use App\Http\Resources\UserCityCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WeatherApiController extends Controller
{
    protected $weatherService;

    public function __construct(OpenWeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function current(Request $request)
    {
        $request->validate(['place' => 'required|string']);
        $weather = $this->weatherService->getWeatherForDate($request->place, now());
        return new WeatherResource($weather);
    }

    public function forecast(Request $request)
    {
        $request->validate(['place' => 'required|string']);
        $forecast = $this->weatherService->getWeatherForDate($request->place, now()->addDay());
        return new WeatherResource($forecast);
    }
}
