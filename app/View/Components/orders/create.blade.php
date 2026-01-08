<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Book New Service') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Success Message --}}
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- The Form --}}
                    <form action="{{ route('order.store') }}" method="POST">
                        @csrf

                        {{-- Service Details --}}
                        <div class="mb-4">
                            <label for="service_details" class="block font-medium text-sm text-gray-700">Service Type</label>
                            <select name="service_details" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                <option value="Washing & Folding">Washing & Folding ($10/kg)</option>
                                <option value="Dry Cleaning">Dry Cleaning (Fixed Price)</option>
                            </select>
                        </div>

                        {{-- Weight Input --}}
                        <div class="mb-4">
                            <label for="weight" class="block font-medium text-sm text-gray-700">Weight (in Kilos)</label>
                            <input type="number" name="weight" step="0.1" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" placeholder="Ex: 2.5" required>
                            <p class="text-sm text-gray-500 mt-1">Total price will be calculated automatically ($10 per kilo).</p>
                        </div>

                        {{-- Submit Button --}}
                        <div class="mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Place Order
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>