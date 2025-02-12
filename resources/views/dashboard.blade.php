<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <main class="relative -mt-[64px]">
        <div class="absolute inset-0 bg-gray-900"></div>
        <div class="relative min-h-screen flex flex-col items-center justify-center px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto">
                <h1 class="text-5xl sm:text-6xl font-bold text-white mb-8">
                    Explore Weather 
                    <span class="block mt-2 text-blue-400">Around the World</span>
                </h1>
                
                <p class="text-xl text-gray-300 mb-12 leading-relaxed">
                    Whether you're planning your next adventure or just curious about the weather, 
                    we've got you covered with real-time updates and accurate forecasts.
                </p>

                <a href="{{ route('weather.search') }}" 
                    class="inline-flex items-center px-8 py-4 text-lg font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-900 transition-all duration-300 transform hover:scale-105">
                    Discover Weather
                    <svg class="ml-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>

            </div>
        </div>
    </main>
</x-app-layout>