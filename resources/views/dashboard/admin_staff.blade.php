<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-2 md:px-4">
            <div>
                <h2 class="font-medium text-xl text-slate-800 uppercase tracking-tighter">
                    {{ auth()->user()->hasRole('ADMIN') ? __('Admin Dashboard') : __('Staff Dashboard') }}
                </h2>
                <p class="text-[11px] font-medium text-slate-500 uppercase tracking-widest mt-1">
                    {{ __('Order Operations & Inventory Control') }}
                </p>
            </div>

            @can('create orders')
                <x-primary-button onclick="window.location='{{ route('orders.create') }}'" 
                    class="!bg-[#475569] hover:!bg-[#334155] !text-white !font-medium px-6 py-2.5 rounded-full shadow-lg transition-all active:scale-95 text-center justify-center uppercase">
                    {{ __('+ ADD WALK-IN') }}
                </x-primary-button>
            @endcan
        </div>
    </x-slot>

    <div x-data="orderSystem()" class="py-8 px-4 md:px-10 max-w-[90rem] mx-auto space-y-10">
        {{-- 1. Global Statistics Counters --}}
        <section class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 md:gap-6">
            {{-- Directly using $stats from Controller --}}
            @foreach($stats as $stat)
                <x-stat-card :label="strtoupper($stat['label'])" :value="$stat['value']" />
            @endforeach
        </section>

        {{-- 2. Advanced Filters --}}
        <nav class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <form action="{{ route('dashboard') }}" method="GET" class="flex flex-col md:flex-row items-stretch md:items-end gap-6">
                <div class="flex-1 flex flex-col gap-2">
                    <label class="text-[10px] font-medium text-slate-500 uppercase tracking-widest ml-1">Order Status</label>
                    <x-filter-select name="status" label="Select Status" onchange="this.form.submit()" class="w-full">
                        <option value="{{ \App\Models\Order::STATUS_ACTIVE }}" {{ request('status') == \App\Models\Order::STATUS_ACTIVE ? 'selected' : '' }}>ACTIVE</option>
                        <option value="{{ \App\Models\Order::STATUS_COMPLETED }}" {{ request('status') == \App\Models\Order::STATUS_COMPLETED ? 'selected' : '' }}>COMPLETED</option>
                        <option value="{{ \App\Models\Order::STATUS_CANCELLED }}" {{ request('status') == \App\Models\Order::STATUS_CANCELLED ? 'selected' : '' }}>CANCELLED</option>
                    </x-filter-select>
                </div>

                <div class="flex-1 flex flex-col gap-2">
                    <label class="text-[10px] font-medium text-slate-500 uppercase tracking-widest ml-1">Service Type</label>
                    <x-filter-select name="service" label="Select Service" onchange="this.form.submit()" class="w-full">
                        <option value="">ALL SERVICES</option>
                        @foreach($mainServices as $service)
                            <option value="{{ $service->id }}" {{ request('service') == $service->id ? 'selected' : '' }}>{{ strtoupper($service->service_name) }}</option>
                        @endforeach
                    </x-filter-select>
                </div>

                <div class="flex-1 flex flex-col gap-2">
                    <label class="text-[10px] font-medium text-slate-500 uppercase tracking-widest ml-1">Payment</label>
                    <x-filter-select name="payment_status" label="Select Status" onchange="this.form.submit()" class="w-full">
                        <option value="">ALL PAYMENTS</option>
                        <option value="{{ \App\Models\Payment::STATUS_PENDING }}" {{ request('payment_status') == \App\Models\Payment::STATUS_PENDING ? 'selected' : '' }}>PENDING</option>
                        <option value="{{ \App\Models\Payment::STATUS_PAID }}" {{ request('payment_status') == \App\Models\Payment::STATUS_PAID ? 'selected' : '' }}>PAID</option>
                    </x-filter-select>
                </div>

                @if(request()->anyFilled(['status', 'service', 'payment_status']))
                    <div class="pb-1 text-center md:text-left">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-[10px] font-black text-rose-500 uppercase tracking-widest bg-rose-50 rounded-xl hover:bg-rose-100 border border-rose-100 transition-colors uppercase">
                            Clear Filters
                        </a>
                    </div>
                @endif
            </form>
        </nav>

        {{-- 3. Operational Table --}}
        <div class="overflow-x-auto rounded-[2rem] shadow-2xl border border-slate-100 bg-white">
            @php
                $operationalHeaders = [
                    ['name' => 'ID', 'width' => '5%'], ['name' => 'CUSTOMER', 'width' => '12%'], ['name' => 'SERVICE DETAILS', 'width' => '15%'],
                    ['name' => 'COLLECTION', 'width' => '13%'], ['name' => 'LAUNDRY STATUS', 'width' => '14%'], ['name' => 'PAYMENT', 'width' => '14%'],
                    ['name' => 'DELIVERY', 'width' => '14%'], ['name' => 'ACTIONS', 'width' => '13%'],
                ];
            @endphp
            <table class="w-full min-w-[1100px]">
                <x-table-header :headers="$operationalHeaders" />
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                        @php 
                            $isCancelled = $order->order_status === \App\Models\Order::STATUS_CANCELLED;
                            $isCompleted = $order->order_status === \App\Models\Order::STATUS_COMPLETED;
                            $terminalState = $isCancelled || $isCompleted; 
                        @endphp
                        <tr class="transition-all duration-200 {{ $isCancelled ? 'bg-rose-50/80' : ($isCompleted ? 'bg-emerald-50/20' : 'hover:bg-slate-50') }}">
                            <td class="px-4 py-8 text-center text-[11px] font-medium text-slate-400 uppercase tracking-widest">#{{ $order->id }}</td>
                            
                            <td class="px-4 py-8 text-center">
                                <button type="button" @click="customer = { name: '{{ $order->user->name }}', email: '{{ $order->user->email }}', phone: '{{ $order->user->contact_no ?? 'N/A' }}', address: '{{ $order->user->address ?? 'N/A' }}' }; openModal = true" 
                                    class="text-[11px] font-medium text-indigo-600 uppercase tracking-tight hover:text-indigo-800 transition-all uppercase">
                                    {{ $order->user->name }}
                                </button>
                            </td>

                            <td class="px-4 py-8">
                                <div class="flex flex-col min-h-[140px] justify-center">
                                    @if($order->services->isNotEmpty() && $order->services->first()->mainService)
                                        <span class="text-[11px] font-medium text-slate-900 uppercase tracking-tight block">{{ $order->services->first()->mainService->service_name }}</span>
                                        <span class="text-[10px] font-medium text-slate-400 uppercase tracking-widest">{{ $order->services->first()->quantity }} {{ (str_contains(strtoupper($order->services->first()->mainService->service_name), 'DRY CLEAN')) ? 'pcs' : 'kg' }}</span>
                                    @endif
                                    <div class="mt-1">
                                        @foreach($order->services as $service)
                                            @foreach($service->addons as $addon)
                                                <span class="text-[9px] font-medium text-indigo-400 uppercase tracking-tight block">+ {{ strtoupper($addon->addon_name) }}</span>
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            </td>

                            <td class="px-2 py-4">
                                <div class="flex flex-col items-center justify-between min-h-[140px] py-4">
                                    <div class="flex flex-col items-center text-center">
                                        <span class="text-[9px] text-slate-400 font-medium uppercase italic tracking-widest">{{ str_replace('_', ' ', $order->collection->collection_method) }}</span>
                                        @if($order->collection->collection_method === 'STAFF_PICKUP' && $order->collection->collection_date)
                                            <span class="mt-1 text-[8px] font-medium text-slate-500 uppercase tracking-tighter"> {{ \Carbon\Carbon::parse($order->collection->collection_date)->format('M d, g:i A') }}</span>
                                        @endif
                                    </div>
                                    <div class="w-full flex justify-center">
                                        @if($terminalState)
                                            <x-order-status-select :currentStatus="$order->collection->collection_status" :terminal="true" :options="[]" />
                                        @else
                                            @can('update order status')
                                                <x-order-status-select :currentStatus="$order->collection->collection_status" :options="[\App\Models\Collection::STATUS_PENDING => 'PENDING', \App\Models\Collection::STATUS_RECEIVED => 'RECEIVED']" @change="performUpdate('{{ route('orders.updateCollection', $order->id) }}', { collection_status: $el.value }, () => { window.location.reload() })" />
                                            @else
                                                <span class="text-[11px] font-medium tracking-tight text-slate-700 uppercase">{{ $order->collection->collection_status }}</span>
                                            @endcan
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="px-2 py-4">
                                <div class="flex flex-col items-center justify-between min-h-[140px] py-4">
                                    <span class="text-[9px] text-slate-400 font-medium uppercase italic tracking-widest"></span>
                                    <div class="w-full flex justify-center">
                                        @if($terminalState)
                                            <x-order-status-select :currentStatus="$order->laundryStatus->current_status" :terminal="true" :options="[]" />
                                        @else
                                            @can('update order status')
                                                <x-order-status-select :currentStatus="$order->laundryStatus->current_status" :terminal="false" :options="[\App\Models\LaundryStatus::PENDING => 'PENDING', \App\Models\LaundryStatus::WASHING => 'WASHING', \App\Models\LaundryStatus::DRYING => 'DRYING', \App\Models\LaundryStatus::FOLDING => 'FOLDING', \App\Models\LaundryStatus::IRONING => 'IRONING', \App\Models\LaundryStatus::READY => 'READY']" @change="performUpdate('{{ route('orders.updateStatus', $order->id) }}', { current_status: $el.value }, () => { window.location.reload() })" />
                                            @else
                                                <span class="text-[11px] font-medium uppercase tracking-tight text-slate-700">{{ $order->laundryStatus->current_status }}</span>
                                            @endcan
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="px-2 py-4">
                                <div class="flex flex-col items-center justify-between min-h-[140px] py-4">
                                    <div class="flex flex-col items-center text-center">
                                        <span class="text-[9px] text-slate-400 font-medium uppercase italic tracking-widest">Amount: Php {{ number_format($order->total_price, 2) }}</span>
                                    </div>
                                    <div class="w-full flex justify-center">
                                        @if($terminalState)
                                            <x-order-status-select :currentStatus="$order->payment->payment_status" :terminal="true" :options="[]" />
                                        @else
                                            @can('process payments')
                                                <x-order-status-select :currentStatus="$order->payment->payment_status" :options="[\App\Models\Payment::STATUS_PENDING => 'PENDING', \App\Models\Payment::STATUS_PAID => 'PAID']" @change="performUpdate('{{ route('orders.updatePayment', $order->id) }}', { payment_status: $el.value }, () => { window.location.reload(); })" />
                                            @else
                                                <x-order-status-badge :status="$order->payment->payment_status" />
                                            @endcan
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="px-2 py-4">
                                <div class="flex flex-col items-center justify-between min-h-[140px] py-4" x-data="{ showScheduleModal: false, scheduledDate: '{{ $order->delivery->scheduled_delivery_date ? $order->delivery->scheduled_delivery_date->format('Y-m-d\TH:i') : '' }}' }">
                                    <div class="flex flex-col items-center text-center">
                                        <span class="text-[9px] text-slate-400 font-medium uppercase italic tracking-widest">{{ str_replace('_', ' ', $order->delivery->delivery_method) }}</span>
                                        @if($order->delivery->scheduled_delivery_date)
                                            <span class="mt-1 text-[8px] font-medium text-slate-500 uppercase tracking-tighter">On {{ $order->delivery->scheduled_delivery_date->format('M d, g:i A') }}</span>
                                        @endif
                                        @if(!$terminalState && $order->delivery->delivery_status !== 'DELIVERED')
                                            @can('update order status')
                                                <button @click="showScheduleModal = true" class="mt-1 text-[9px] font-medium uppercase italic text-indigo-600 hover:text-indigo-800 tracking-widest border-b border-indigo-200 uppercase">Set Sched</button>
                                            @endcan
                                        @endif
                                    </div>
                                    <div class="w-full flex justify-center">
                                        <x-order-status-select :currentStatus="$order->delivery->delivery_status" :terminal="$terminalState" :options="['READY'=>'READY','DELIVERED'=>'DELIVERED']" @change="performUpdate('{{ route('orders.updateDelivery', $order->id) }}', { delivery_status: $el.value }, () => { window.location.reload() })" />
                                    </div>
                                    <div x-show="showScheduleModal" x-cloak class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[10000] flex items-center justify-center p-4">
                                        <div @click.away="showScheduleModal = false" class="bg-white rounded-2xl p-6 w-full max-w-sm shadow-2xl border border-slate-100 text-left">
                                            <h4 class="text-sm font-medium uppercase italic tracking-widest text-slate-800 mb-4 uppercase border-b pb-2">SET DELIVERY DATE</h4>
                                            <input type="datetime-local" x-model="scheduledDate" class="w-full border-2 border-slate-100 rounded-xl p-3 text-sm font-medium text-slate-700 mb-4 uppercase">
                                            <div class="flex justify-end gap-3">
                                                <button type="button" @click="showScheduleModal = false" class="px-4 py-2 text-[10px] font-medium uppercase text-slate-400">Cancel</button>
                                                <button type="button" @click="performUpdate('{{ route('orders.setDeliverySchedule', $order->id) }}', { scheduled_date: scheduledDate }, () => { window.location.reload() })" class="bg-indigo-600 text-white px-5 py-2 rounded-xl font-medium text-[10px] uppercase shadow-lg shadow-indigo-100">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-8 text-center">
                                <div class="flex flex-col items-center justify-center min-h-[140px] gap-3">
                                    <button @click="selectedOrder = @js($order->load(['audits' => fn($q) => $q->orderBy('changed_at', 'desc'), 'payment', 'delivery'])); showDetails = true" 
                                        class="text-[10px] font-medium text-indigo-500 uppercase tracking-widest hover:text-indigo-700 transition-colors bg-indigo-50/50 px-4 py-2 rounded-lg border border-indigo-100 w-full shadow-sm uppercase">
                                        VIEW TRACKING
                                    </button>
                                    @if($order->isCancellable())
                                        @can('cancel any order', $order)
                                            <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="w-full inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="text-rose-500 font-medium text-[10px] uppercase tracking-widest bg-rose-50/50 px-4 py-2 rounded-lg border border-rose-100 w-full transition-all shadow-sm uppercase" onclick="return confirm('Archive entry?')">CANCEL</button>
                                            </form>
                                        @endcan
                                    @endif
                                    <div class="flex flex-col gap-1 items-center pt-1">
                                        @if($isCancelled) <span class="text-[9px] font-medium text-rose-400 uppercase tracking-widest">CANCELLED</span> @endif
                                        @if($isCompleted) <span class="text-[9px] font-medium text-emerald-500 uppercase tracking-widest">COMPLETED</span> @endif
                                        @if(!$terminalState) <span class="text-[9px] font-medium text-slate-300 uppercase tracking-widest">ACTIVE</span> @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="py-24 text-center text-[11px] font-medium text-slate-400 uppercase tracking-[0.2em]">No operational entries found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
            <div class="mt-10 px-4">
                <div class="flex flex-col md:flex-row justify-between items-center gap-6 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                    
                    {{-- 1. Custom Status Text (Left) --}}
                    <div class="text-[10px] font-medium text-slate-400 uppercase tracking-widest">
                        Showing <span class="text-slate-900">{{ $orders->firstItem() }}</span> 
                        to <span class="text-slate-900">{{ $orders->lastItem() }}</span> 
                        of <span class="text-slate-900">{{ $orders->total() }}</span> entries
                    </div>

                    {{-- 2. Component-Based Navigation (Right) --}}
                    <div class="flex items-center gap-2">
                        {{-- Previous Page Button --}}
                        @if ($orders->onFirstPage())
                            <button disabled class="px-4 py-2 text-[10px] font-bold text-slate-300 uppercase tracking-widest bg-slate-50 rounded-xl cursor-not-allowed">
                                Previous
                            </button>
                        @else
                            <x-secondary-button onclick="window.location='{{ $orders->previousPageUrl() }}'" class="!text-[10px] !font-bold !px-4 !py-2 !rounded-xl uppercase">
                                Previous
                            </x-secondary-button>
                        @endif

                        {{-- Next Page Button --}}
                        @if ($orders->hasMorePages())
                            <x-secondary-button onclick="window.location='{{ $orders->nextPageUrl() }}'" class="!text-[10px] !font-bold !px-4 !py-2 !rounded-xl uppercase">
                                Next
                            </x-secondary-button>
                        @else
                            <button disabled class="px-4 py-2 text-[10px] font-bold text-slate-300 uppercase tracking-widest bg-slate-50 rounded-xl cursor-not-allowed">
                                Next
                            </button>
                        @endif
                    </div>

                </div>
            </div>
        @endif

        <x-customer-modal x-show="openModal">
            <div class="space-y-6 text-[11px] font-medium uppercase tracking-widest text-slate-500">
                <div class="flex justify-between items-center"><span class="text-slate-300">E-MAIL:</span> <span x-text="customer.email" class="lowercase tracking-normal text-slate-700 uppercase"></span></div>
                <div class="flex justify-between items-center"><span class="text-slate-300">CONTACT NO:</span> <span x-text="customer.phone" class="text-slate-700 uppercase"></span></div>
                <div class="flex justify-between items-center"><span class="text-slate-300">ADDRESS:</span> <span x-text="customer.address" class="text-slate-700 text-right ml-4 leading-tight font-normal uppercase"></span></div>
            </div>
        </x-customer-modal>

        <x-tracking-modal x-show="showDetails">
            <div class="bg-slate-50 p-6 md:p-8 rounded-2xl mb-10 space-y-4 border border-slate-100">
                <h4 class="text-[10px] font-medium text-slate-400 uppercase tracking-[0.2em] mb-2 uppercase font-medium">Primary Milestones</h4>
                <div class="flex justify-between items-center text-[11px] uppercase tracking-widest uppercase font-medium"><span class="text-slate-500">Payment:</span><span class="text-emerald-600 font-medium uppercase" x-text="selectedOrder.payment?.payment_date ? new Date(selectedOrder.payment.payment_date).toLocaleString() : 'Pending'"></span></div>
                <div class="flex justify-between items-center text-[11px] uppercase tracking-widest uppercase font-medium"><span class="text-slate-500">Target Schedule:</span><span class="text-indigo-600 font-medium uppercase" x-text="selectedOrder.delivery?.scheduled_delivery_date ? new Date(selectedOrder.delivery.scheduled_delivery_date).toLocaleString() : 'Not set'"></span></div>
            </div>
            <h4 class="text-[11px] text-slate-400 uppercase font-medium mb-6 px-1 uppercase font-medium">Operational History</h4>
            <template x-for="audit in [...new Map(selectedOrder.audits.map(item => [item.new_status + item.changed_at, item])).values()]" :key="audit.id">
                <div class="relative pl-10 pb-10 border-l border-slate-100 last:border-0 ml-2 font-medium">
                    <div class="absolute -left-[5.5px] top-1 w-2.5 h-2.5 rounded-full border-2 border-white bg-indigo-500 shadow-sm"></div>
                    <div class="flex flex-col gap-1.5 text-left font-normal uppercase">
                        <span class="text-[13px] font-medium text-slate-800 uppercase tracking-tight" x-text="audit.new_status"></span>
                        <span class="text-[10px] text-slate-400 uppercase tracking-tighter" x-text="new Date(audit.changed_at).toLocaleString('en-PH', { hour12: true })"></span>
                        <p class="text-[10px] text-slate-400 mt-1 lowercase italic uppercase" x-text="audit.old_status ? 'Transitioned from ' + audit.old_status.toLowerCase() : 'Entry initiated'"></p>
                    </div>
                </div>
            </template>
        </x-tracking-modal>
    </div>
</x-app-layout>

<script>
    function orderSystem() {
        return {
            openModal: false, customer: {}, showDetails: false, selectedOrder: { audits: [] },
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
                    if (response.status === 200) { successCallback(); } 
                    else if (response.status === 403) { alert('Unauthorized Access.'); window.location.reload(); }
                    else { alert('Transaction failed: Entry status lock.'); }
                } catch (error) { console.error('Network Error:', error); alert('Signal loss: Check local internet connection.'); }
            }
        }
    }
</script>