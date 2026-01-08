<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Book a Service') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('book.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Select Service:</label>
                            <select name="service_id" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Choose a Service --</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->service_id }}">
                                        {{ $service->service_name }} (${{ $service->service_base_price }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Quantity (Kg / Items):</label>
                            <input type="number" name="quantity" min="1" max="15" step="0.1" required 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Max 15kg per order">
                            <p class="text-xs text-gray-500 mt-1">For orders larger than 15kg, please make two separate bookings.</p>
                        </div>

                        <div class="flex items-center justify-between" style="display: flex; justify-content: space-between; align-items: center;">
                            <a href="{{ route('dashboard') }}" style="color: #666; text-decoration: underline;">Cancel</a>
                            
                            <button type="submit" style="background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;">
                                Place Order
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>