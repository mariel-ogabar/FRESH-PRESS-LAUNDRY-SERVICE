<x-app-layout>
    <style>[x-cloak] { display: none !important; }</style>

    <div x-data="orderSystem()">
        <x-slot name="header">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-2 md:px-4">
                <div>
                    <h1 class="font-medium text-xl text-slate-800 uppercase tracking-tighter">{{ __('Service Protocols') }}</h1>
                    <p class="text-[11px] font-medium text-slate-500 uppercase tracking-widest mt-1">Operational history and real-time tracking</p>
                </div>
                <x-primary-button onclick="window.location='{{ route('orders.create') }}'" 
                    class="!bg-[#475569] hover:!bg-[#334155] !rounded-full !py-3 !px-8 !text-[10px] !font-medium tracking-[0.2em] shadow-lg transition-all active:scale-95 uppercase">
                    {{ __('BOOK NEW SERVICE') }}
                </x-primary-button>
            </div>
        </x-slot>

        <div class="py-8 px-4 md:px-10 max-w-[90rem] mx-auto space-y-10">
            
            <div class="overflow-x-auto rounded-[2.5rem] shadow-2xl border border-slate-100 bg-white">
                <table class="w-full text-left border-collapse min-w-[1100px]">
                    <thead class="bg-slate-50/50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] text-center">Protocol ID</th>
                            <th class="px-6 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em]">Configuration</th>
                            <th class="px-6 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] text-center">Laundry Progress</th>
                            <th class="px-6 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] text-center">Collection Schedule</th>
                            <th class="px-6 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] text-center">Return Schedule</th>
                            <th class="px-6 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] text-center">Financials</th>
                            <th class="px-6 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($orders as $order)
                        @php 
                            $isCancelled = $order->order_status === \App\Models\Order::STATUS_CANCELLED;
                        @endphp
                        {{-- Row turns soft rose if cancelled to match Admin aesthetic --}}
                        <tr class="transition-all duration-300 {{ $isCancelled ? 'bg-rose-50/40' : 'hover:bg-slate-50/50' }}">
                            <td class="px-6 py-10 text-center">
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block mb-2">#{{ $order->id }}</span>
                                <x-order-status-badge :status="$order->order_status" />
                            </td>

                            <td class="px-6 py-10">
                                <div class="text-[11px] font-medium text-slate-800 uppercase tracking-tight">
                                    {{ $order->services->first()->mainService->service_name ?? 'UNDEFINED' }}
                                </div>
                                <div class="text-[10px] text-slate-400 mt-1 uppercase font-medium tracking-widest">
                                    {{ $order->services->first()->quantity ?? 0 }} 
                                    {{ (str_contains(strtoupper($order->services->first()->mainService->service_name ?? ''), 'DRY CLEAN')) ? 'pcs' : 'kg' }}
                                </div>
                                <div class="flex flex-wrap gap-1.5 mt-3">
                                    @foreach($order->services as $service)
                                        @foreach($service->addons as $addon)
                                            <span class="text-[9px] text-indigo-400 font-medium uppercase tracking-tight">+ {{ strtoupper($addon->addon_name) }}</span>
                                        @endforeach
                                    @endforeach
                                </div>
                            </td>

                            <td class="px-6 py-10 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <x-order-status-badge :status="$order->laundryStatus->current_status" />
                                    @php
                                        $statuses = [\App\Models\LaundryStatus::PENDING, \App\Models\LaundryStatus::WASHING, \App\Models\LaundryStatus::DRYING, \App\Models\LaundryStatus::FOLDING, \App\Models\LaundryStatus::IRONING, \App\Models\LaundryStatus::READY];
                                        $currentIdx = array_search($order->laundryStatus->current_status, $statuses);
                                        $progress = (($currentIdx + 1) / count($statuses)) * 100;
                                    @endphp
                                    <div class="w-20 bg-slate-100 rounded-full h-1 mt-4 overflow-hidden shadow-inner">
                                        <div class="bg-indigo-500 h-1 rounded-full transition-all duration-1000 ease-out" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-10 text-center">
                                <div class="space-y-2">
                                    @if($order->collection->collection_method === 'STAFF_PICKUP' && $order->collection->collection_date)
                                        <span class="text-[10px] font-medium text-slate-500 uppercase tracking-tighter">{{ \Carbon\Carbon::parse($order->collection->collection_date)->format('M d, g:i A') }}</span>
                                    @else
                                        <span class="text-[9px] text-slate-400 font-medium uppercase italic tracking-widest block">{{ str_replace('_', ' ', $order->collection->collection_method) }}</span>
                                    @endif
                                    <x-order-status-badge :status="$order->collection->collection_status" />
                                </div>
                            </td>

                            <td class="px-6 py-10 text-center">
                                <div class="space-y-2">
                                    @if($order->delivery->scheduled_delivery_date)
                                        <span class="text-[10px] font-medium text-slate-500 uppercase tracking-tighter">On {{ \Carbon\Carbon::parse($order->delivery->scheduled_delivery_date)->format('M d, g:i A') }}</span>
                                    @else
                                        <span class="text-[9px] text-slate-400 font-medium uppercase italic tracking-widest block">PENDING SCHEDULE</span>
                                    @endif
                                    <x-order-status-badge :status="$order->delivery->delivery_status" />
                                </div>
                            </td>

                            <td class="px-6 py-10 text-center">
                                <div class="text-[11px] font-medium text-slate-400 uppercase italic tracking-widest mb-2">Php {{ number_format($order->total_price, 2) }}</div>
                                <x-order-status-badge :status="$order->payment->payment_status" />
                            </td>

                            <td class="px-6 py-10 text-center">
                                <div class="flex flex-col gap-3 items-center justify-center min-w-[140px]">
                                    {{-- SAFE serialization for Alpine tracking - Matches Staff Dashboard Style --}}
                                    <button type="button" 
                                            @click="selectedOrder = @js($order->load(['audits' => fn($q) => $q->orderBy('changed_at', 'desc'), 'payment', 'delivery'])); showDetails = true" 
                                            class="w-full text-[10px] font-medium text-indigo-500 uppercase tracking-widest py-2 px-4 bg-indigo-50/50 rounded-lg border border-indigo-100 hover:text-indigo-700 transition-all active:scale-95 shadow-sm">
                                        VIEW TRACKING
                                    </button>
                                    @if($order->isCancellable() && !$isCancelled)
                                        @can('cancel', $order)
                                            <form action="{{ route('orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure to cancel this order?')">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="w-full text-rose-500 font-medium text-[10px] uppercase tracking-widest bg-rose-50/50 px-4 py-2 rounded-lg border border-rose-100 transition-all shadow-sm">
                                                    CANCEL
                                                </button>
                                            </form>
                                        @endcan
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-40 text-center">
                                <p class="text-[11px] font-medium text-slate-400 uppercase tracking-[0.3em]">No operational service records identified.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
                <div class="mt-10 px-4">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>

        {{-- Tracking Modal (Midnight style consistency) --}}
        <x-tracking-modal x-show="showDetails" x-cloak>
            <div class="bg-[#121826] p-8 rounded-[2rem] mb-10 shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 right-0 p-6 opacity-5 text-white pointer-events-none">
                    <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/></svg>
                </div>
                
                <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.4em] mb-8 border-b border-slate-800 pb-4 font-medium">Operational Summary</h4>
                <div class="grid grid-cols-2 gap-8 text-[11px] uppercase tracking-[0.2em] font-medium">
                    <div class="space-y-1">
                        <span class="text-slate-500 italic block">Financials</span>
                        <span class="text-white" x-text="selectedOrder.payment?.payment_status || 'PENDING'"></span>
                    </div>
                    <div class="space-y-1">
                        <span class="text-slate-500 italic block">Target Schedule</span>
                        <span class="text-indigo-400" x-text="selectedOrder.delivery?.scheduled_delivery_date ? new Date(selectedOrder.delivery.scheduled_delivery_date).toLocaleDateString() : 'PROCESSING...'"></span>
                    </div>
                </div>
            </div>

            <h4 class="text-[11px] text-slate-400 font-bold uppercase tracking-[0.3em] mb-10 px-2 font-medium">Operational Audit Trail</h4>
            <div class="space-y-10 relative before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-[1px] before:bg-slate-100 font-medium">
                <template x-for="audit in selectedOrder.audits" :key="audit.id">
                    <div class="relative pl-12 group">
                        <div class="absolute left-0 top-1 w-6 h-6 bg-white border-2 border-indigo-500 rounded-full flex items-center justify-center z-10 shadow-sm transition-transform group-hover:scale-110">
                            <div class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[13px] font-medium text-slate-800 uppercase tracking-tight" x-text="audit.new_status"></span>
                            <span class="text-[10px] text-slate-400 uppercase tracking-widest mt-1" x-text="new Date(audit.changed_at).toLocaleString('en-PH', { hour12: true })"></span>
                        </div>
                    </div>
                </template>
            </div>
        </x-tracking-modal>
    </div>
</x-app-layout>

<script>
    function orderSystem() {
        return {
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
                    if (response.status === 200) { successCallback(); } 
                    else if (response.status === 403) { alert('Unauthorized Access.'); window.location.reload(); }
                    else { alert('Transaction failed.'); }
                } catch (error) { console.error('Network Error:', error); }
            }
        }
    }
</script>