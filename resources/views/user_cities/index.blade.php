<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('My Cities') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="mb-4 bg-green-500 text-white p-4 rounded-md">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('user_cities.store') }}" method="POST" class="mb-8">
                    @csrf
                    <div class="flex">
                        <input type="text" name="city" placeholder="Add a new city" required
                               class="flex-1 rounded-l-md bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-r-md hover:bg-blue-700 transition duration-300 ease-in-out">
                            Add City
                        </button>
                    </div>
                </form>

                <div class="space-y-4">
                    @if($favoriteCity)
                        <div class="bg-gray-700 rounded-lg overflow-hidden">
                            <div class="p-4 flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <span class="text-xl font-semibold text-gray-200">{{ $favoriteCity->city }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <form action="{{ route('weather.current') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="city" value="{{ $favoriteCity->city }}">
                                        <button type="submit" class="text-blue-500 hover:text-blue-400 transition duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </form>
                                    <form action="{{ route('user_cities.toggle_favorite', $favoriteCity) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-yellow-500 hover:text-yellow-400 transition duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                            </svg>
                                        </button>
                                    </form>
                                    <form action="{{ route('user_cities.toggle_forecast', $favoriteCity) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-{{ $favoriteCity->send_forecast ? 'emerald' : 'blue' }}-500 hover:text-{{ $favoriteCity->send_forecast ? 'emerald' : 'blue' }}-400 transition duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </button>
                                    </form>
                                    <form action="{{ route('user_cities.destroy', $favoriteCity) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-400 transition duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                    @foreach($otherCities as $city)
                        <div class="bg-gray-700 rounded-lg overflow-hidden">
                            <div class="p-4 flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <span class="text-xl font-semibold text-gray-200">{{ $city->city }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <form action="{{ route('weather.current') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="city" value="{{ $city->city }}">
                                        <button type="submit" class="text-blue-500 hover:text-blue-400 transition duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </form>
                                    <form action="{{ route('user_cities.toggle_favorite', $city) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-blue-500 hover:text-yellow-500 transition duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                            </svg>
                                        </button>
                                    </form>
                                    <form action="{{ route('user_cities.toggle_forecast', $city) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-{{ $city->send_forecast ? 'emerald' : 'blue' }}-500 hover:text-{{ $city->send_forecast ? 'emerald' : 'blue' }}-400 transition duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </button>
                                    </form>
                                    <form action="{{ route('user_cities.destroy', $city) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-400 transition duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>