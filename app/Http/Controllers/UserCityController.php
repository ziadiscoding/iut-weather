<?php

namespace App\Http\Controllers;

use App\Models\UserCity;
use Illuminate\Http\Request;

class UserCityController extends Controller
{
    public function index()
    {
        $favoriteCity = auth()->user()->cities()->where('is_favorite', true)->first();
        $otherCities = auth()->user()->cities()->where('is_favorite', false)->get();
        return view('user_cities.index', compact('favoriteCity', 'otherCities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'city' => 'required|string|max:255',
        ]);

        $city = auth()->user()->cities()->create([
            'city' => $request->city,
        ]);

        return redirect()->route('user_cities.index')->with('success', 'City added successfully.');
    }

    public function toggleFavorite(UserCity $city)
    {
        if ($city->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$city->is_favorite) {
            UserCity::setFavorite($city->id, auth()->id());
            $message = 'City set as favorite.';
        } else {
            $city->update(['is_favorite' => false]);
            $message = 'City removed from favorites.';
        }

        return redirect()->route('user_cities.index')->with('success', $message);
    }

    public function toggleForecast(UserCity $city)
    {
        if ($city->user_id !== auth()->id()) {
            abort(403);
        }

        $city->update(['send_forecast' => !$city->send_forecast]);
        return back()->with('success', 'Forecast settings updated.');
    }

    public function destroy(UserCity $city)
    {
        if ($city->user_id !== auth()->id()) {
            abort(403);
        }
        
        $city->delete();
        return redirect()->route('user_cities.index')->with('success', 'City removed successfully.');
    }
}