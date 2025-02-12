<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-900">
            <nav class="bg-gray-800 border-b border-gray-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <div class="shrink-0 flex items-center">
                                <h1 class="font-semibold text-xl text-gray-200">WeatherApp</h1>
                            </div>
                        </div>

                        @if (Route::has('login'))
                            <div class="flex items-center space-x-4">
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="text-gray-300 hover:text-white transition">Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition">Log in</a>
                                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Register</a>
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
            </nav>

            <main class="relative -mt-[64px]">
                <div class="absolute inset-0 bg-gray-900"></div>

                <div class="relative min-h-screen flex flex-col items-center justify-center px-4 sm:px-6 lg:px-8 pt-[128px]">
                    <div class="text-center max-w-3xl mx-auto">
                        <h1 class="text-5xl sm:text-6xl font-bold text-white mb-8">
                            Track Weather 
                            <span class="block mt-2 text-blue-400">Around the World</span>
                        </h1>
                        
                        <p class="text-xl text-gray-300 mb-12 leading-relaxed">
                            Stay informed about weather conditions worldwide with real-time updates and accurate forecasts.
                        </p>

                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-8 py-4 text-lg font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition transform hover:scale-105">
                                Access Dashboard
                                <svg class="ml-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        @else
                            <div class="flex flex-col sm:flex-row justify-center gap-4">
                                <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 text-lg font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition transform hover:scale-105">
                                    Get Started
                                    <svg class="ml-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                                <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-4 text-lg font-medium text-white bg-gray-800 rounded-lg hover:bg-gray-700 transition">
                                    Sign In
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>