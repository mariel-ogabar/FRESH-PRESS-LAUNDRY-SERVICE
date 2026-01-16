<x-app-layout>
    <div x-data="orderSystem()">
        <x-slot name="header">
            <div>
                <h1>
                    {{-- Updated to Spatie hasRole --}}
                    {{ auth()->user()->hasRole('ADMIN') ? 'Admin Dashboard' : 'Staff Dashboard' }}
                </h1>
                <p>Manage all Customer orders</p>
                
                {{-- Updated to check permission --}}
                @can('create orders')
                    <a href="{{ route('orders.create') }}">
                        + Add walk-in
                    </a>
                @endcan
            </div>
        </x-slot>

        <div>
            <div>
                <section>
                    @php
                        $stats = [
                            ['label' => 'Total Orders', 'value' => $orders->count()],
                            ['label' => 'Pending', 'value' => $orders->where('laundryStatus.current_status', \App\Models\LaundryStatus::PENDING)->count()],
                            ['label' => 'In Progress', 'value' => $orders->whereIn('laundryStatus.current_status', [\App\Models\LaundryStatus::WASHING, \App\Models\LaundryStatus::DRYING, \App\Models\LaundryStatus::FOLDING, \App\Models\LaundryStatus::IRONING])->count()],
                            ['label' => 'Ready', 'value' => $orders->where('laundryStatus.current_status', \App\Models\LaundryStatus::READY)->count()],
                            ['label' => 'Paid Sales', 'value' => 'Php ' . number_format($orders->where('payment.payment_status', \App\Models\Payment::STATUS_PAID)->sum('total_price'), 2)],
                            ['label' => 'To be Paid', 'value' => 'Php ' . number_format($orders->where('payment.payment_status', \App\Models\Payment::STATUS_PENDING)->sum('total_price'), 2)],
                        ];
                    @endphp

                    @foreach($stats as $stat)
                        <div>
                            <span>{{ $stat['label'] }}</span>
                            <h3>{{ $stat['value'] }}</h3>
                        </div>
                    @endforeach
                </section>

                <nav>
                    <form action="{{ route('dashboard') }}" method="GET">
                        <select name="status" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="{{ \App\Models\Order::STATUS_ACTIVE }}" {{ request('status') == \App\Models\Order::STATUS_ACTIVE ? 'selected' : '' }}>Active</option>
                            <option value="{{ \App\Models\Order::STATUS_COMPLETED }}" {{ request('status') == \App\Models\Order::STATUS_COMPLETED ? 'selected' : '' }}>Completed</option>
                            <option value="{{ \App\Models\Order::STATUS_CANCELLED }}" {{ request('status') == \App\Models\Order::STATUS_CANCELLED ? 'selected' : '' }}>Cancelled</option>
                        </select>

                        <select name="service" onchange="this.form.submit()">
                            <option value="">All Services</option>
                            @foreach($mainServices as $service)
                                <option value="{{ $service->id }}" {{ request('service') == $service->id ? 'selected' : '' }}>
                                    {{ $service->service_name }}
                                </option>
                            @endforeach
                        </select>

                        <select name="payment" onchange="this.form.submit()">
                            <option value="">All Payment</option>
                            <option value="{{ \App\Models\Payment::STATUS_PENDING }}" {{ request('payment') == \App\Models\Payment::STATUS_PENDING ? 'selected' : '' }}>Pending</option>
                            <option value="{{ \App\Models\Payment::STATUS_PAID }}" {{ request('payment') == \App\Models\Payment::STATUS_PAID ? 'selected' : '' }}>Paid</option>
                        </select>

                        @if(request()->anyFilled(['status', 'service', 'payment']))
                            <a href="{{ route('dashboard') }}">Clear Filters</a>
                        @endif
                    </form>
                </nav>

                <main>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Service Details</th>
                                <th>Collection</th>
                                <th>Laundry Status</th>
                                <th>Payment</th>
                                <th>Delivery</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    
                                    <td>
                                        <button type="button" @click="customer = { 
                                            name: '{{ $order->user->name }}', 
                                            email: '{{ $order->user->email }}', 
                                            phone: '{{ $order->user->contact_no ?? 'N/A' }}', 
                                            address: '{{ $order->user->address ?? 'N/A' }}' 
                                        }; openModal = true" style="background: none; border: none; color: blue; text-decoration: underline; cursor: pointer;">
                                            {{ $order->user->name }}
                                        </button>
                                    </td>

                                    <td>
                                        @if($order->services->isNotEmpty() && $order->services->first()->mainService)
                                            <strong>{{ $order->services->first()->mainService->service_name }}</strong>
                                            <br>
                                            @php
                                                $mainService = $order->services->first()->mainService;
                                                $serviceName = strtoupper($mainService->service_name);
                                                $isPerPiece = str_contains($serviceName, 'DRY CLEAN') || str_contains($serviceName, 'STAIN');
                                                $unit = $isPerPiece ? 'pcs' : ($mainService->unit ?? 'kg');
                                            @endphp
                                            <span>{{ $order->services->first()->quantity }} {{ $unit }}</span>
                                        @else
                                            <span class="text-red-500 italic">No Service Linked</span>
                                        @endif
                                        
                                        {{-- Add-ons display --}}
                                        <div>
                                            @foreach($order->services as $service)
                                                @foreach($service->addons as $addon)
                                                    <span class="text-xs text-gray-500">+ {{ $addon->addon_name }}</span>
                                                @endforeach
                                            @endforeach
                                        </div>
                                    </td>

                                    <td>
                                        <div x-data="{ colStatus: '{{ $order->collection->collection_status }}' }">
                                            <div style="margin-bottom: 5px;">
                                                <strong style="font-size: 10px; text-transform: uppercase;">
                                                    {{ str_replace('_', ' ', $order->collection->collection_method) }}
                                                </strong>
                                            </div>

                                            @can('update order status') {{-- Consistent permission name --}}
                                                <select x-model="colStatus" 
                                                        @change="performUpdate('{{ route('orders.updateCollection', $order->id) }}', { collection_status: $el.value }, () => { window.dispatchEvent(new CustomEvent('col-updated-{{ $order->id }}', { detail: $el.value })) })" 
                                                        style="font-size: 11px;">
                                                    <option value="{{ \App\Models\Collection::STATUS_PENDING }}">PENDING</option>
                                                    <option value="{{ \App\Models\Collection::STATUS_RECEIVED }}">RECEIVED</option>
                                                </select>
                                            @else
                                                <span style="font-size: 11px;">{{ $order->collection->collection_status }}</span>
                                            @endcan
                                        </div>
                                    </td>

                                    <td style="padding: 16px;">
                                        <div x-data="{ 
                                            lauStatus: '{{ $order->laundryStatus->current_status }}',
                                            isClickable: '{{ $order->collection->collection_status }}' === '{{ \App\Models\Collection::STATUS_RECEIVED }}'
                                        }" 
                                        @col-updated-{{ $order->id }}.window="isClickable = ($event.detail === '{{ \App\Models\Collection::STATUS_RECEIVED }}')">
                                            
                                            @can('update order status') {{-- Matches the others now --}}
                                                <select x-model="lauStatus" 
                                                        @change="performUpdate('{{ route('orders.updateStatus', $order->id) }}', { current_status: $el.value }, () => { window.location.reload() })" 
                                                        :disabled="!isClickable" 
                                                        style="font-size: 11px; font-weight: bold; width: 100%; display: block;"
                                                        :style="!isClickable ? 'opacity: 0.5; cursor: not-allowed;' : 'cursor: pointer; background: #fff; border: 1px solid #000;'">
                                                    
                                                    <option value="{{ \App\Models\LaundryStatus::PENDING }}">PENDING</option>
                                                    <option value="{{ \App\Models\LaundryStatus::WASHING }}">WASHING</option>
                                                    <option value="{{ \App\Models\LaundryStatus::DRYING }}">DRYING</option>
                                                    <option value="{{ \App\Models\LaundryStatus::FOLDING }}">FOLDING</option>
                                                    <option value="{{ \App\Models\LaundryStatus::IRONING }}">IRONING</option>
                                                    <option value="{{ \App\Models\LaundryStatus::READY }}">READY</option>
                                                </select>

                                                <template x-if="!isClickable">
                                                    <div style="font-size: 9px; color: #dc2626; margin-top: 4px;">Receive items first</div>
                                                </template>
                                            @else
                                                <span style="font-size: 11px; font-weight: bold;">{{ $order->laundryStatus->current_status }}</span>
                                            @endcan
                                        </div>
                                    </td>

                                    <td>
                                        <div x-data="{ payStatus: '{{ $order->payment->payment_status }}' }">
                                            <strong>Php {{ number_format($order->total_price, 2) }}</strong>
                                            
                                            {{-- Admin check or specific permission --}}
                                            @can('process payments')
                                                <select x-model="payStatus" 
                                                        @change="performUpdate('{{ route('orders.updatePayment', $order->id) }}', { payment_status: $el.value }, () => { window.location.reload(); })" 
                                                        style="font-size: 11px; display: block; width: 100%; margin-top: 4px;">
                                                    <option value="{{ \App\Models\Payment::STATUS_PENDING }}">PENDING</option>
                                                    <option value="{{ \App\Models\Payment::STATUS_PAID }}">PAID</option>
                                                </select>
                                            @else
                                                <div style="margin-top: 4px;">
                                                    <x-order-status-badge :status="$order->payment->payment_status" class="block mt-1" />
                                                </div>
                                            @endcan
                                        </div>
                                    </td>

                                    <td x-data="{ 
                                        showScheduleModal: false, 
                                        scheduledDate: '{{ $order->delivery->scheduled_delivery_date ? $order->delivery->scheduled_delivery_date->format('Y-m-d\TH:i') : '' }}' 
                                    }">
                                        <div class="flex flex-col space-y-2">
                                            {{-- Delivery Method Label --}}
                                            <strong style="font-size: 10px; text-transform: uppercase; color: #6b7280;">
                                                {{ str_replace('_', ' ', $order->delivery->delivery_method) }}
                                            </strong>

                                            {{-- Permission Check: Dapat tugma sa Seeder mo --}}
                                            @can('update order status')
                                                <select @change="performUpdate('{{ route('orders.updateDelivery', $order->id) }}', { delivery_status: $el.value }, () => { window.location.reload() })"
                                                        style="font-size: 11px; padding: 2px;" class="border rounded">
                                                    <option value="READY" {{ $order->delivery->delivery_status == 'READY' ? 'selected' : '' }}>READY</option>
                                                    <option value="DELIVERED" {{ $order->delivery->delivery_status == 'DELIVERED' ? 'selected' : '' }}>DELIVERED</option>
                                                </select>

                                                @if($order->delivery->delivery_status !== 'DELIVERED')
                                                    <button @click="showScheduleModal = true" 
                                                            style="font-size: 9px; padding: 2px 5px; cursor: pointer; border: 1px solid #ddd; background: #f9f9f9;">
                                                        {{ $order->delivery->scheduled_delivery_date ? 'Change Sched' : 'Set Sched' }}
                                                    </button>
                                                @endif
                                            @else
                                                {{-- Kung walang permission, text lang ang makikita --}}
                                                <span style="font-size: 11px; font-weight: bold; color: #374151;">
                                                    {{ $order->delivery->delivery_status }}
                                                </span>
                                                @if($order->delivery->scheduled_delivery_date)
                                                    <small style="font-size: 9px; color: #6b7280;">
                                                        Sched: {{ $order->delivery->scheduled_delivery_date->format('M d, g:i A') }}
                                                    </small>
                                                @endif
                                            @endcan
                                        </div>

                                        {{-- Schedule Modal --}}
                                        <div x-show="showScheduleModal" 
                                            x-transition
                                            style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index: 10000; display: flex; align-items: center; justify-content: center;">
                                            <div @click.away="showScheduleModal = false" style="background:white; padding:20px; border-radius: 8px; width: 300px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);">
                                                <h4 style="margin-bottom: 10px; font-weight: bold;">Set Delivery Date</h4>
                                                <input type="datetime-local" x-model="scheduledDate" style="width: 100%; border: 1px solid #ccc; padding: 5px; margin-bottom: 10px;">
                                                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                                                    <button @click="showScheduleModal = false" style="font-size: 11px; color: #6b7280;">Cancel</button>
                                                    <button @click="performUpdate('{{ route('orders.setDeliverySchedule', $order->id) }}', { scheduled_date: scheduledDate }, () => { window.location.reload() })" 
                                                            style="background: #4f46e5; color: white; padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; font-size: 11px;">
                                                        Save Schedule
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" 
                                                @click="selectedOrder = { audits: [] }; 
                                                        selectedOrder = {{ $order->toJson() }}; 
                                                        showDetails = true">
                                            View Tracking
                                        </button>
                                        @if($order->isCancellable())
                                            {{-- This checks the Policy 'cancel' method --}}
                                            @can('cancel any order', $order)
                                                <form action="{{ route('orders.cancel', $order->id) }}" method="POST" style="display:inline;">
                                                    @csrf 
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900 font-bold underline" 
                                                            onclick="return confirm('Are you sure you want to cancel this order?')">
                                                        CANCEL
                                                    </button>
                                                </form>
                                            @endcan
                                        @endif

                                        @if($order->order_status !== \App\Models\Order::STATUS_ACTIVE)
                                            <span>{{ $order->order_status }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8">No Orders Found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </main>
            </div>
        </div>

        <div x-show="openModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index: 9999; display: flex; align-items: center; justify-content: center;">
            <div @click.away="openModal = false" style="background:white; padding:20px; border-radius: 8px; width: 300px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <h3 x-text="customer.name" style="font-weight: bold; font-size: 18px; margin-bottom: 10px;"></h3>
                <p><strong>Email:</strong> <span x-text="customer.email"></span></p>
                <p><strong>Phone:</strong> <span x-text="customer.phone"></span></p>
                <p><strong>Address:</strong> <span x-text="customer.address"></span></p>
                <button @click="openModal = false" style="margin-top: 15px; width: 100%; background: #333; color: white; padding: 5px; cursor: pointer;">Close</button>
            </div>
        </div>

        <div x-show="showDetails" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index: 9999; display: flex; align-items: center; justify-content: center;">
            <div @click.away="showDetails = false" style="background:white; padding:25px; border-radius: 12px; width: 480px; max-height: 85vh; overflow-y: auto; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
                <h3 style="font-weight: bold; font-size: 20px; margin-bottom: 20px; border-bottom: 2px solid #f3f4f6; padding-bottom: 10px;">
                    Tracking #<span x-text="selectedOrder.id"></span>
                </h3>

                <div style="background: #f8fafc; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    <h4 style="font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; font-weight: bold; margin-bottom: 10px;">Timestamps</h4>
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-size: 13px; color: #475569;">Payment Received:</span>
                        <span style="font-size: 13px; font-weight: bold; color: #059669;" 
                            x-text="selectedOrder.payment.payment_date ? new Date(selectedOrder.payment.payment_date).toLocaleString() : 'Pending'"></span>
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-size: 13px; color: #475569;">Target Schedule:</span>
                        <span style="font-size: 13px; font-weight: bold; color: #2563eb;" 
                            x-text="selectedOrder.delivery.scheduled_delivery_date ? new Date(selectedOrder.delivery.scheduled_delivery_date).toLocaleString() : 'Not set'"></span>
                    </div>

                    <div style="display: flex; justify-content: space-between;">
                        <span style="font-size: 13px; color: #475569;">Actual Delivery:</span>
                        <span style="font-size: 13px; font-weight: bold; color: #4f46e5;" 
                            x-text="selectedOrder.delivery.delivered_date ? new Date(selectedOrder.delivery.delivered_date).toLocaleString() : 'In process'"></span>
                    </div>
                </div>

                <h4 style="font-size: 11px; color: #64748b; text-transform: uppercase; font-weight: bold; margin-bottom: 15px;">Progress History</h4>

                <template x-for="audit in [...new Map(selectedOrder.audits.map(item => [item.new_status + item.changed_at, item])).values()]" :key="audit.id">
                    <div style="border-left: 2px solid #6366f1; padding-left: 15px; margin-bottom: 15px; position: relative;">
                        <div style="width: 8px; height: 8px; background: #6366f1; border-radius: 50%; position: absolute; left: -5px; top: 5px;"></div>
                        
                        <b x-text="audit.new_status" style="text-transform: uppercase; color: #4f46e5; font-size: 14px;"></b><br>
                        <small x-text="new Date(audit.changed_at).toLocaleString('en-PH', { hour12: true })" style="color: #6b7280; font-size: 11px;"></small>
                        
                        <p x-text="audit.old_status ? 'From ' + audit.old_status : 'Order Initiated'" style="font-size: 12px; margin-top: 5px; color: #4b5563;"></p>
                    </div>
                </template>

                <button @click="showDetails = false" style="margin-top: 20px; width: 100%; background: #f1f5f9; color: #1e293b; border: none; padding: 10px; border-radius: 6px; font-weight: bold; cursor: pointer;">Close History</button>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function orderSystem() {
        return {
            openModal: false,
            customer: {},
            showDetails: false,
            selectedOrder: { audits: [] },

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

                    if (response.status === 200) {
                        successCallback();
                    } 
                    else if (response.status === 403) {
                        alert('Unauthorized: Wala kang permiso na gawin ito.');
                        window.location.reload(); 
                    } 
                    else if (response.status === 422) {
                        const data = await response.json();
                        alert('Error: ' + (data.message || 'Invalid data.'));
                    }
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