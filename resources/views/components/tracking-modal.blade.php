@props(['selectedOrder' => null])

<div x-show="showDetails" 
     x-cloak 
     class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[9999] flex items-center justify-center p-4 md:p-6"
     style="display: none;">
    
    <div @click.away="showDetails = false" 
         class="bg-white rounded-[2rem] w-full max-w-lg shadow-2xl border border-slate-100 max-h-[90vh] flex flex-col overflow-hidden transition-all">
        
        {{-- Header: Centered Architecture Standard --}}
        <div class="bg-slate-50/50 px-8 py-10 border-b border-slate-100 text-center">
            <h3 class="text-xl font-medium text-slate-800 uppercase tracking-tighter">
                Tracking #<span x-text="selectedOrder.id"></span>
            </h3>
            <p class="text-[9px] font-medium text-indigo-500 uppercase tracking-[0.25em] mt-1.5">Audit Log & System Progression</p>
            <div class="mt-4 w-12 h-1 bg-indigo-500/20 mx-auto rounded-full"></div>
        </div>

        {{-- Scrollable Content --}}
        <div class="flex-grow overflow-y-auto custom-scrollbar px-10 py-10">
            
            {{-- 1. Primary Milestones: Key-Value Logic --}}
            <div class="mb-12">
                <h4 class="text-[10px] font-bold text-slate-300 uppercase tracking-[0.2em] mb-6">Primary Milestones</h4>
                <div class="divide-y divide-slate-50 border-y border-slate-50">
                    {{-- Payment Row --}}
                    <div class="py-4 flex justify-between items-center">
                        <span class="text-[10px] font-medium text-slate-400 uppercase tracking-widest">Payment Status</span>
                        <span class="text-[11px] font-medium uppercase" 
                              :class="selectedOrder.payment?.payment_date ? 'text-emerald-600' : 'text-rose-400'"
                              x-text="selectedOrder.payment?.payment_date ? new Date(selectedOrder.payment.payment_date).toLocaleString('en-PH', {dateStyle:'medium', timeStyle:'short'}) : 'Awaiting Payment'"></span>
                    </div>

                    {{-- Schedule Row --}}
                    <div class="py-4 flex justify-between items-center">
                        <span class="text-[10px] font-medium text-slate-400 uppercase tracking-widest">Target Schedule</span>
                        <span class="text-[11px] font-medium text-indigo-600 uppercase" 
                              x-text="selectedOrder.delivery?.scheduled_delivery_date ? new Date(selectedOrder.delivery.scheduled_delivery_date).toLocaleString('en-PH', {dateStyle:'medium', timeStyle:'short'}) : 'Unscheduled'"></span>
                    </div>

                    {{-- Delivery Row --}}
                    <div class="py-4 flex justify-between items-center">
                        <span class="text-[10px] font-medium text-slate-400 uppercase tracking-widest">Delivered Date</span>
                        <span class="text-[11px] font-medium text-slate-700 uppercase" 
                              x-text="selectedOrder.delivery?.delivered_date ? new Date(selectedOrder.delivery.delivered_date).toLocaleString('en-PH', {dateStyle:'medium', timeStyle:'short'}) : 'In Transit'"></span>
                    </div>
                </div>
            </div>

            {{-- 2. Operational History --}}
            <h4 class="text-[10px] font-bold text-slate-300 uppercase tracking-[0.2em] mb-10">Operational History</h4>
            <div class="relative ml-2">
                <template x-for="(audit, index) in selectedOrder.audits" :key="audit.id">
                    <div class="relative pl-10 pb-12 border-l border-slate-100 last:border-0">
                        {{-- Timeline Indicator --}}
                        <div class="absolute -left-[6px] top-1 w-3 h-3 rounded-full border-2 border-white shadow-sm transition-colors"
                             :class="index === 0 ? 'bg-indigo-500 ring-4 ring-indigo-50' : 'bg-slate-200'"></div>
                        
                        <div class="flex flex-col gap-1">
                            <span class="text-[13px] font-medium text-slate-800 uppercase tracking-tight" x-text="audit.new_status"></span>
                            <span class="text-[10px] text-slate-400 font-medium uppercase tracking-tighter" 
                                  x-text="new Date(audit.changed_at).toLocaleString('en-PH', { hour: '2-digit', minute: '2-digit', hour12: true, month:'short', day:'numeric' })"></span>
                            
                            <div class="mt-2 inline-flex">
                                <p class="text-[9px] px-2 py-0.5 bg-slate-50 text-slate-400 rounded border border-slate-100 uppercase tracking-tighter italic" 
                                   x-text="audit.old_status ? 'Transitioned from ' + audit.old_status.toLowerCase() : 'Log Initiation'"></p>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Footer Action --}}
        <div class="px-8 py-6 bg-slate-50/30 border-t border-slate-100 flex justify-end">
            <x-primary-button @click="showDetails = false" 
                class="!bg-slate-800 hover:!bg-slate-700 !rounded-xl uppercase !text-[9px] !tracking-[0.2em] !px-8 !py-3 !m-0 !shadow-md transition-all active:scale-95">
                Dismiss Log Archive
            </x-primary-button>
        </div>
    </div>
</div>