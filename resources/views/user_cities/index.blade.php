<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('My Cities') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('user_cities.store') }}" method="POST" class="mb-8">
                    @csrf
                    <div class="flex">
                        <input type="text" name="city" placeholder="Add a new city" required
                               class="flex-1 rounded-l-md bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-r-md hover:bg-blue-700 transition duration-300 ease-in-out transform hover:scale-105">
                            <i class="fas fa-plus mr-2"></i> Add City
                        </button>
                    </div>
                </form>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($cities as $city)
                        <div class="bg-gray-700 rounded-lg shadow-lg overflow-hidden transform transition duration-300 hover:scale-105">
                            <div class="p-5">
                                <h3 class="text-xl font-semibold text-gray-200 mb-3">{{ $city->city }}</h3>
                                <div class="flex justify-between items-center">
                                    <form action="{{ route('user_cities.toggle_favorite', $city) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-2xl {{ $city->is_favorite ? 'text-yellow-400' : 'text-gray-400' }} hover:text-yellow-300 transition duration-300">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('user_cities.toggle_forecast', $city) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-2xl {{ $city->send_forecast ? 'text-green-400' : 'text-gray-400' }} hover:text-green-300 transition duration-300">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('user_cities.destroy', $city) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-2xl text-gray-400 hover:text-red-400 transition duration-300">
                                            <i class="fas fa-trash-alt"></i>
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