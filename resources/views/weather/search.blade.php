<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Weather Search') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <h1 class="text-3xl font-bold text-gray-100 mb-8 text-center">Weather Forecast Search</h1>

                    <form action="{{ route('weather.current') }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-300 mb-2">City</label>
                                <input type="text" name="city" id="city" placeholder="Enter city name" value="{{ old('city') }}" required
                                       class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-md text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('city')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-300 mb-2">Date</label>
                                <input type="date" name="date" id="date" value="{{ old('date', now()->format('Y-m-d')) }}" required
                                       class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-md text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('date')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <button type="submit"
                                        class="w-full px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-colors duration-300">
                                    Search Weather
                                </button>
                            </div>
                        </div>
                    </form>

                    @if(session('error'))
                        <div class="mt-6 bg-red-600 text-white p-4 rounded-md">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>