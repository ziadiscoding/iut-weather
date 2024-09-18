@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Current Weather for {{ $city }}</h1>
    <p>Temperature: {{ $weather['main']['temp'] }}°C</p>
    <p>Feels like: {{ $weather['main']['feels_like'] }}°C</p>
    <p>Description: {{ $weather['weather'][0]['description'] }}</p>
    <p>Humidity: {{ $weather['main']['humidity'] }}%</p>
    <p>Wind Speed: {{ $weather['wind']['speed'] }} m/s</p>
    
    <a href="{{ route('weather.search') }}" class="btn btn-primary mt-3">Search Another City</a>
</div>
@endsection