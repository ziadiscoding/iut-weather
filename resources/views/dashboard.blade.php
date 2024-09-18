<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-700">
                    <h1 class="text-3xl font-bold mb-8 text-center text-gray-200">Weather Dashboard</h1>

                    <!-- Quick Search -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold mb-4 text-gray-300">Quick Weather Search</h2>
                        <form action="{{ route('weather.current') }}" method="POST" class="flex items-center space-x-4">
                            @csrf
                            <input type="text" name="city" placeholder="Enter city name" required
                                class="px-4 py-2 bg-gray-700 text-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-600 flex-grow">
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-opacity-50 transition-colors duration-300">
                                Search
                            </button>
                        </form>
                    </div>

                    <!-- Favorite Cities Weather -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4 text-gray-300">Your Favorite Cities</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach(['New York', 'London', 'Tokyo'] as $city) <!-- Replace with actual user's favorite cities -->
                            <div class="bg-gray-700 rounded-lg shadow-lg p-4">
                                <h3 class="text-lg font-semibold mb-2 text-gray-200">{{ $city }}</h3>
                                <p class="text-3xl font-bold text-gray-200">22°C</p> <!-- Replace with actual temperature -->
                                <p class="text-sm text-gray-400">Partly Cloudy</p> <!-- Replace with actual weather description -->
                                <a href="{{ route('weather.current', ['city' => $city]) }}" class="mt-2 inline-block text-blue-400 hover:text-blue-300">
                                    View Details →
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Weather Map -->
                    <!-- Weather Map -->
                    <div class="mt-8">
                        <h2 class="text-xl font-semibold mb-4 text-gray-300">Weather Map</h2>
                        <div id="weathermap" class="bg-gray-700 rounded-lg shadow-lg h-96"></div>
                    </div>

                    <!-- Recent Searches -->
                    <div class="mt-8">
                        <h2 class="text-xl font-semibold mb-4 text-gray-300">Recent Searches</h2>
                        <div class="bg-gray-700 rounded-lg shadow-lg p-4">
                            <ul class="space-y-2">
                                @foreach(['Paris', 'Berlin', 'Sydney'] as $recentCity) <!-- Replace with actual recent searches -->
                                <li>
                                    <a href="{{ route('weather.current', ['city' => $recentCity]) }}" class="text-blue-400 hover:text-blue-300">
                                        {{ $recentCity }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>