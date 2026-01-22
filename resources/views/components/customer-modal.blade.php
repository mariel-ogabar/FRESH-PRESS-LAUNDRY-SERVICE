<div {{ $attributes->merge(['class' => 'fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[9999] flex items-center justify-center p-4']) }} 
     x-cloak 
     style="display: none;">
    
    <div @click.away="openModal = false" 
         class="bg-white rounded-[2rem] w-full max-w-sm shadow-2xl border border-slate-100 overflow-hidden transition-all">
        
        {{-- Header --}}
        <div class="bg-slate-50/50 px-8 py-10 border-b border-slate-100 text-center">
            <h3 x-text="customer.name" class="text-xl font-medium text-slate-800 uppercase tracking-tighter"></h3>
            <div class="mt-3 w-10 h-1 bg-indigo-500/20 mx-auto rounded-full"></div>
        </div>
        
        {{-- Body --}}
        <div class="px-10 py-8">
            {{ $slot }}
        </div>

        {{-- Footer --}}
        <div class="px-8 py-8 bg-slate-50/30 border-t border-slate-100">
            <div class="flex flex-row items-center justify-center gap-3 w-full">                
                <x-primary-button @click="openModal = false" 
                    class="!bg-slate-800 hover:!bg-slate-700 !rounded-xl uppercase !text-[9px] !tracking-[0.2em] !px-6 !py-3 !m-0 !shadow-md">
                    Dismiss Profile
                </x-primary-button>
            </div>
        </div>
    </div>
</div>