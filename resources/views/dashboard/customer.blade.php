<x-app-layout>
    <div x-data="{ 
        showDetails: false, 
        selectedOrder: { audits: [] } 
    }">
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">My Laundry Orders</h1>
                    <p class="text-sm text-gray-600">Track and manage your service requests</p>
                </div>
                <a href="{{ route('orders.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-indigo-700 transition">
                    Book New Service
                </a>
            </div>
        </x-slot>

        <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Order History</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 border-b text-sm font-bold text-gray-700">ID</th>
                            <th class="px-4 py-3 border-b text-sm font-bold text-gray-700">Date</th>
                            <th class="px-4 py-3 border-b text-sm font-bold text-gray-700">Service & Add-ons</th>
                            <th class="px-4 py-3 border-b text-sm font-bold text-gray-700">Laundry Status</th>
                            <th class="px-4 py-3 border-b text-sm font-bold text-gray-700">Collection</th>
                            <th class="px-4 py-3 border-b text-sm font-bold text-gray-700">Delivery/Return</th>
                            <th class="px-4 py-3 border-b text-sm font-bold text-gray-700">Payment</th>
                            <th class="px-4 py-3 border-b text-sm font-bold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-4 border-b">
                                <span class="font-bold text-indigo-600">#{{ $order->id }}</span><br>
                                <x-order-status-badge :status="$order->order_status" />
                            </td>

                            <td class="px-4 py-4 border-b text-sm text-gray-600">
                                {{ $order->created_at->format('M d, Y') }}
                            </td>

                            <td class="px-4 py-4 border-b">
                                <div class="text-sm font-bold text-gray-800">{{ $order->services->first()->mainService->service_name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">
                                    Qty: {{ $order->services->first()->quantity ?? 0 }} 
                                    {{ (str_contains(strtoupper($order->services->first()->mainService->service_name ?? ''), 'DRY CLEAN')) ? 'pcs' : 'kg' }}
                                </div>
                                <div class="mt-1">
                                    @foreach($order->services as $service)
                                        @foreach($service->addons as $addon)
                                            <span class="text-[10px] bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded mr-1">+ {{ $addon->addon_name }}</span>
                                        @endforeach
                                    @endforeach
                                </div>
                            </td>

                            <td class="px-4 py-4 border-b">
                                @php
                                    $statuses = [
                                        \App\Models\LaundryStatus::PENDING,
                                        \App\Models\LaundryStatus::WASHING,
                                        \App\Models\LaundryStatus::DRYING,
                                        \App\Models\LaundryStatus::FOLDING,
                                        \App\Models\LaundryStatus::IRONING,
                                        \App\Models\LaundryStatus::READY
                                    ];
                                    $currentStatus = $order->laundryStatus->current_status;
                                    $currentIdx = array_search($currentStatus, $statuses);
                                    $progress = (($currentIdx + 1) / count($statuses)) * 100;
                                @endphp
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-gray-700 uppercase">{{ str_replace('_', ' ', $currentStatus) }}</span>
                                    <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1.5">
                                        <div class="bg-indigo-600 h-1.5 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-4 border-b text-sm">
                                <span class="font-medium {{ $order->collection->collection_status === \App\Models\Collection::STATUS_PENDING ? 'text-orange-600' : 'text-green-600' }}">
                                    {{ $order->collection->collection_status === \App\Models\Collection::STATUS_PENDING ? 'Waiting for Shop' : 'Received by Shop' }}
                                </span><br>
                                <small class="text-gray-400 uppercase text-[10px]">{{ str_replace('_', ' ', $order->collection->collection_method) }}</small>
                            </td>

                            <td class="px-4 py-4 border-b text-sm">
                                @if($order->delivery->delivery_status === \App\Models\Delivery::STATUS_DELIVERED)
                                    <span class="text-green-600 font-bold">Claimed / Delivered</span>
                                @elseif($order->laundryStatus->current_status === \App\Models\LaundryStatus::READY)
                                    <span class="text-indigo-600 font-bold animate-pulse">Ready for {{ $order->delivery->delivery_method === 'DELIVERY' ? 'Delivery' : 'Pickup' }}</span>
                                @else
                                    <span class="text-gray-400 italic">Processing...</span>
                                @endif
                                <br>
                                <small class="text-gray-400 uppercase text-[10px]">{{ str_replace('_', ' ', $order->delivery->delivery_method) }}</small>
                            </td>

                            <td class="px-4 py-4 border-b">
                                <div class="font-bold text-gray-800">₱{{ number_format($order->total_price, 2) }}</div>
                                <x-order-status-badge :status="$order->payment->payment_status" />
                            </td>

                            <td class="px-4 py-4 border-b">
                                <div class="flex flex-col space-y-2">
                                        <button type="button" 
                                                @click="selectedOrder = {{ $order->load('audits')->toJson() }}; showDetails = true" 
                                                class="text-xs font-bold uppercase py-1 px-3 bg-indigo-50 text-indigo-600 rounded border border-indigo-100 hover:bg-indigo-600 hover:text-white transition">
                                            View Tracking
                                        </button>
                                    @if($order->isCancellable())
                                        @can('cancel', $order)
                                            <form action="{{ route('orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="w-full text-xs bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded transition font-bold">
                                                    Cancel Order
                                                </button>
                                            </form>
                                        @endcan
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-500 italic">No orders found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <x-tracking-timeline-modal />
    </div>
</x-app-layout>

<script>
    function orderSystem() {
        return {
            openModal: false,
            customer: {},
            showDetails: false,
            selectedOrder: { audits: [] },

            /**
             * Centralized Update Function
             * @param {string} url 
             * @param {object} payload 
             * @param {function} successCallback 
             */
            async performUpdate(url, payload, successCallback) {
                try {
                    const response = await fetch(url, {
                        method: 'PATCH',
                        headers: { 
                            'Content-Type': 'application/json', 
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content 
                        },
                        body: JSON.stringify(payload)
                    });

                    // 1. Success handling (Status 200)
                    if (response.status === 200) {
                        successCallback();
                    } 
                    // 2. Permission handling (Status 403 - Forbidden)
                    else if (response.status === 403) {
                        alert('Unauthorized: Wala kang permiso na gawin ito.');
                        window.location.reload(); 
                    } 
                    // 3. Validation or Logic error (Status 422 o 400)
                    else if (response.status === 422) {
                        const data = await response.json();
                        alert('Error: ' + (data.message || 'Invalid data.'));
                    }
                    // 4. Server Error handling (Status 500)
                    else {
                        alert('May nangyaring mali sa server. Pakisubukang muli.');
                    }

                } catch (error) {
                    console.error('Network Error:', error);
                    alert('Hindi makakonekta sa server. I-check ang iyong internet connection.');
                }
            }
        }
    }
</script>