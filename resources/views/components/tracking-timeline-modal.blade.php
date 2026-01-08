<div x-show="showDetails" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/60" 
     style="display:none;">
    
    <div @click.away="showDetails = false" class="bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden border border-gray-100">
        <div class="bg-indigo-600 px-6 py-4 flex justify-between items-center">
            <h3 class="text-white font-bold text-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d=" incumbents-9 5l7 7-7 7"></path></svg>
                Order #<span x-text="selectedOrder.id"></span> Tracking
            </h3>
            <button @click="showDetails = false" class="text-indigo-100 hover:text-white text-2xl leading-none">&times;</button>
        </div>
        
        <div class="p-8 max-h-[60vh] overflow-y-auto bg-gray-50">
            <div class="relative border-l-2 border-indigo-200 ml-3">
                <template x-for="(audit, index) in selectedOrder.audits" :key="audit.id">
                    <div class="mb-8 ml-6 last:mb-0">
                        <span class="absolute -left-[11px] mt-1 w-5 h-5 rounded-full border-4 border-white shadow-sm"
                              :class="index === 0 ? 'bg-indigo-600 animate-pulse' : 'bg-indigo-300'">
                        </span>
                        
                        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                            <b class="text-sm text-indigo-700 uppercase tracking-wide font-black" x-text="audit.new_status"></b>
                            <div class="text-[10px] text-gray-400 font-medium mt-1 mb-2" x-text="new Date(audit.changed_at).toLocaleString()"></div>
                            <p class="text-xs text-gray-600 italic" x-text="audit.old_status ? 'Progressed from ' + audit.old_status : 'Order successfully initialized'"></p>
                        </div>
                    </div>
                </template>

                <template x-if="selectedOrder.audits && selectedOrder.audits.length === 0">
                    <div class="mb-8 ml-6">
                         <span class="absolute -left-[11px] mt-1 w-5 h-5 rounded-full border-4 border-white bg-indigo-600 shadow-sm"></span>
                         <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                            <b class="text-sm text-indigo-700 uppercase font-black tracking-wide">ORDER PLACED</b>
                            <p class="text-xs text-gray-600 mt-1">Waiting for the shop to process your laundry.</p>
                         </div>
                    </div>
                </template>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-white border-t border-gray-100 text-right">
            <div class="mt-4 p-4 bg-indigo-50/50 rounded-xl border border-indigo-100">
                <h4 class="text-[11px] font-black text-indigo-400 uppercase tracking-widest mb-3">Delivery Information</h4>
                
                <template x-if="selectedOrder.delivery.scheduled_delivery_date && !selectedOrder.delivery.delivered_date">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-600">Target Schedule:</span>
                        <span class="text-xs font-bold text-indigo-700" 
                            x-text="new Date(selectedOrder.delivery.scheduled_delivery_date).toLocaleString('en-US', { dateStyle: 'medium', timeStyle: 'short' })">
                        </span>
                    </div>
                </template>

                <template x-if="selectedOrder.delivery.delivered_date">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-600">Actual Delivery:</span>
                            <span class="text-xs font-bold text-green-600" 
                                x-text="new Date(selectedOrder.delivery.delivered_date).toLocaleString('en-US', { dateStyle: 'medium', timeStyle: 'short' })">
                            </span>
                        </div>
                        <div x-show="selectedOrder.delivery.scheduled_delivery_date" class="text-[10px] text-gray-400 text-right italic">
                            Scheduled was: <span x-text="new Date(selectedOrder.delivery.scheduled_delivery_date).toLocaleDateString()"></span>
                        </div>
                    </div>
                </template>

                <template x-if="!selectedOrder.delivery.scheduled_delivery_date && !selectedOrder.delivery.delivered_date">
                    <span class="text-xs text-gray-400 italic font-medium">Waiting for shop to set delivery schedule...</span>
                </template>
            </div>

</div>