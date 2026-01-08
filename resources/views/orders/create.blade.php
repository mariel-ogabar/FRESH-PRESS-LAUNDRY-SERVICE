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
                    
                    <h3 class="text-lg font-bold mb-4">Washing & Folding Service</h3>
                    
                    <form action="{{ route('order.store') }}" method="POST">
                        @csrf
                        
                        {{-- Weight Input --}}
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Weight (in kg)</label>
                            <input type="number" name="weight" step="0.1" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" placeholder="e.g. 3.5" required>
                            <p class="text-sm text-gray-500 mt-1">Price is $10 per kilo.</p>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex items-center gap-4">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                Calculate & Place Order
                            </button>
                            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 underline">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>