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
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserCityApiController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $cities = $request->user()->cities()->paginate(10);
        return new UserCityCollection($cities);
    }

    public function store(Request $request)
    {
        $request->validate(['city' => 'required|string|max:255']);
        
        $city = $request->user()->cities()->create([
            'city' => $request->city
        ]);

        return new UserCityResource($city);
    }

    public function toggleForecast(UserCity $place)
    {
        $this->authorize('update', $place);
        
        $place->update([
            'send_forecast' => !$place->send_forecast
        ]);

        return new UserCityResource($place);
    }

    public function toggleFavorite(UserCity $place)
    {
        $this->authorize('update', $place);
        
        if (!$place->is_favorite) {
            UserCity::setFavorite($place->id, auth()->id());
        } else {
            $place->update(['is_favorite' => false]);
        }

        return new UserCityResource($place);
    }

    public function destroy(UserCity $place)
    {
        $this->authorize('delete', $place);
        
        $place->delete();
        
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}