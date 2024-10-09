<?php

namespace App\Http\Controllers;

use App\Models\UserCity;
use Illuminate\Http\Request;

class UserCityController extends Controller
{
    public function index()
    {
        $cities = auth()->user()->cities;
        return view('user_cities.index', compact('cities'));
    }

    public function store(Request $request)
    {
        $request->validate(['city' => 'required|string|max:255']);

        auth()->user()->cities()->create([
            'city' => $request->city,
        ]);

        return redirect()->route('user_cities.index')->with('success', 'City added successfully.');
    }

    public function destroy(UserCity $userCity)
    {
        $this->authorize('delete', $userCity);
        $userCity->delete();
        return redirect()->route('user_cities.index')->with('success', 'City removed successfully.');
    }

    public function toggleFavorite(UserCity $userCity)
    {
        $this->authorize('update', $userCity);
        $userCity->update(['is_favorite' => !$userCity->is_favorite]);
        return redirect()->route('user_cities.index')->with('success', 'Favorite status updated.');
    }

    public function toggleForecast(UserCity $userCity)
    {
        $this->authorize('update', $userCity);
        $userCity->update(['send_forecast' => !$userCity->send_forecast]);
        return redirect()->route('user_cities.index')->with('success', 'Forecast status updated.');
    }
}