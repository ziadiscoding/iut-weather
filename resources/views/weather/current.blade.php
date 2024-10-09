<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Weather Information') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <h1 class="text-3xl font-bold text-gray-100 mb-8 text-center">Weather Information</h1>

                    <!-- Search Form -->
                    <form action="{{ route('weather.current') }}" method="POST" class="mb-8">
                        @csrf
                        <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                            <div class="w-full sm:w-1/3">
                                <input type="text" name="city" id="city" placeholder="Enter city name" value="{{ $city ?? old('city') }}" required
                                    class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-md text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="w-full sm:w-1/3">
                                <input type="date" name="date" id="date" value="{{ $date->format('Y-m-d') ?? old('date', now()->format('Y-m-d')) }}" required
                                    class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-md text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="w-full sm:w-1/3">
                                <button type="submit"
                                    class="w-full px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-colors duration-300">
                                    Search
                                </button>
                            </div>
                        </div>
                    </form>

                    @if(isset($city) && isset($weather))
                    <h2 class="text-2xl font-semibold text-gray-100 mb-6 text-center">Weather for {{ $city }}</h2>

                    <div class="mb-8 flex flex-wrap justify-center space-x-2">
                        @foreach ($dates as $dateInfo)
                        <form action="{{ route('weather.current') }}" method="POST" class="inline mb-2">
                            @csrf
                            <input type="hidden" name="city" value="{{ $city }}">
                            <input type="hidden" name="date" value="{{ $dateInfo['formatted'] }}">
                            <button type="submit" class="px-4 py-2 rounded-full text-sm font-semibold transition-colors duration-300 {{ $dateInfo['date']->isSameDay($date) ? 'bg-blue-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' }}">
                                {{ $dateInfo['label'] }}
                            </button>
                        </form>
                        @endforeach
                    </div>

                    <div class="bg-gradient-to-r from-blue-800 to-indigo-900 rounded-lg shadow-lg p-6 mb-8">
                        <h3 class="text-2xl font-semibold text-gray-100 mb-4">{{ $date->format('F j, Y') }}</h3>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-5xl font-bold text-gray-100">{{ round($weather['main']['temp']) }}°C</p>
                                <p class="text-xl text-gray-300">Feels like {{ round($weather['main']['feels_like']) }}°C</p>
                                <p class="text-lg text-gray-300 capitalize mt-2">{{ $weather['weather'][0]['description'] }}</p>
                            </div>
                            <div class="text-right">
                                <img src="http://openweathermap.org/img/wn/{{ $weather['weather'][0]['icon'] }}@4x.png" alt="{{ $weather['weather'][0]['description'] }}" class="w-32 h-32">
                            </div>
                        </div>
                    </div>

                    <!-- Bento Box Layout for Additional Info -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                        @if(isset($coordinates))
                        <div class="bg-gray-700 p-4 rounded-lg shadow">
                            <h3 class="text-gray-400 text-sm mb-2">Coordinates:</h3>
                            <p class="text-2xl text-gray-100 font-bold">{{ $coordinates['lat'] }}, {{ $coordinates['lon'] }}</p>
                        </div>
                        @endif
                        <div class="bg-gray-700 p-4 rounded-lg shadow">
                            <h3 class="text-gray-400 text-sm mb-2">Humidity</h3>
                            <p class="text-2xl text-gray-100 font-bold">{{ $weather['main']['humidity'] }}%</p>
                        </div>
                        <div class="bg-gray-700 p-4 rounded-lg shadow">
                            <h3 class="text-gray-400 text-sm mb-2">Wind Speed</h3>
                            <p class="text-2xl text-gray-100 font-bold">{{ round($weather['wind']['speed'] * 3.6, 1) }} km/h</p>
                        </div>
                        <div class="bg-gray-700 p-4 rounded-lg shadow">
                            <h3 class="text-gray-400 text-sm mb-2">Pressure</h3>
                            <p class="text-2xl text-gray-100 font-bold">{{ $weather['main']['pressure'] }} hPa</p>
                        </div>
                        <div class="bg-gray-700 p-4 rounded-lg shadow">
                            <h3 class="text-gray-400 text-sm mb-2">Visibility</h3>
                            <p class="text-2xl text-gray-100 font-bold">{{ $weather['visibility'] / 1000 ?? 'N/A' }} km</p>
                        </div>
                        @if(isset($weather['sys']['sunrise']))
                        <div class="bg-gray-700 p-4 rounded-lg shadow">
                            <h3 class="text-gray-400 text-sm mb-2">Sunrise</h3>
                            <p class="text-2xl text-gray-100 font-bold">{{ \Carbon\Carbon::createFromTimestamp($weather['sys']['sunrise'])->format('H:i') }}</p>
                        </div>
                        @endif
                        @if(isset($weather['sys']['sunset']))
                        <div class="bg-gray-700 p-4 rounded-lg shadow">
                            <h3 class="text-gray-400 text-sm mb-2">Sunset</h3>
                            <p class="text-2xl text-gray-100 font-bold">{{ \Carbon\Carbon::createFromTimestamp($weather['sys']['sunset'])->format('H:i') }}</p>
                        </div>
                        @endif
                        <div class="bg-gray-700 p-4 rounded-lg shadow">
                            <h3 class="text-gray-400 text-sm mb-2">Cloudiness</h3>
                            <p class="text-2xl text-gray-100 font-bold">{{ $weather['clouds']['all'] }}%</p>
                        </div>
                        @if(isset($weather['uvi']))
                        <div class="bg-gray-700 p-4 rounded-lg shadow">
                            <h3 class="text-gray-400 text-sm mb-2">UV Index</h3>
                            <p class="text-2xl text-gray-100 font-bold">{{ $weather['uvi'] }}</p>
                        </div>
                        @endif
                    </div>
                    @else
                    <p class="text-center text-gray-300 text-xl">Enter a city name and date to see the weather information.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>