<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Laundry Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- 1. Welcome Message --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-bold">Welcome, {{ Auth::user()->name }}</h3>
                    </div>

                    {{-- 2. Success Notification --}}
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-100 text-green-700 border border-green-400 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- 3. THE NEW BOOKING FORM --}}
                    <div class="mb-8 p-6 bg-blue-50 border border-blue-200 rounded-lg">
                        <h4 class="font-bold text-lg mb-4 text-blue-800">+ Book New Service</h4>
                        
                        <p class="text-gray-600 mb-4">Ready to get your laundry done? Click below to book a new service.</p>
                        
                        <a href="{{ route('book.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            📦 Book New Service
                        </a>
                    </div>

                    {{-- 4. ORDER HISTORY TABLE --}}
                    <div class="mb-4 flex justify-between items-center">
                        <h4 class="font-bold text-md">Your Order History</h4>
                        
                        {{-- Sorting Options --}}
                        <div class="flex gap-2 text-sm">
                            <span class="text-gray-600">Sort by:</span>
                            <a href="{{ route('dashboard', ['sort' => 'created_at', 'direction' => ($sortBy == 'created_at' && $sortDirection == 'desc' ? 'asc' : 'desc')]) }}" 
                               class="px-3 py-1 rounded {{ $sortBy == 'created_at' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                Date {{ $sortBy == 'created_at' ? ($sortDirection == 'desc' ? '↓' : '↑') : '' }}
                            </a>
                            <a href="{{ route('dashboard', ['sort' => 'total_price', 'direction' => ($sortBy == 'total_price' && $sortDirection == 'desc' ? 'asc' : 'desc')]) }}" 
                               class="px-3 py-1 rounded {{ $sortBy == 'total_price' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                Price {{ $sortBy == 'total_price' ? ($sortDirection == 'desc' ? '↓' : '↑') : '' }}
                            </a>
                            <a href="{{ route('dashboard', ['sort' => 'order_status', 'direction' => ($sortBy == 'order_status' && $sortDirection == 'asc' ? 'desc' : 'asc')]) }}" 
                               class="px-3 py-1 rounded {{ $sortBy == 'order_status' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                Status {{ $sortBy == 'order_status' ? ($sortDirection == 'asc' ? '↓' : '↑') : '' }}
                            </a>
                        </div>
                    </div>
                    
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service Details</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-widider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if(isset($orders) && count($orders) > 0)
                                @foreach($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">#{{ $order->order_id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $order->created_at->format('M j, Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ $order->created_at->format('g:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @foreach($order->services as $service)
                                            {{ $service->mainService->service_name }} ({{ $service->quantity }})
                                            @if(!$loop->last), @endif
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">${{ number_format($order->total_price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($order->order_status == 'ACTIVE') bg-green-100 text-green-800
                                            @elseif($order->order_status == 'CANCELLED') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $order->order_status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($order->order_status == 'ACTIVE')
                                            <a href="{{ route('order.edit', $order->order_id) }}" class="text-blue-600 hover:text-blue-900 text-sm mr-3">Edit</a>
                                            <form action="{{ route('order.cancel', $order->order_id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm" onclick="return confirm('Are you sure you want to cancel this order?')">Cancel</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No orders found. Click the blue button above to start!</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>