<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-2 md:px-4">
            <div>
                <h2 class="font-medium text-xl text-slate-800 uppercase tracking-tighter">{{ __('Service Management') }}</h2>
                <p class="text-[11px] font-medium text-slate-500 uppercase tracking-widest mt-1">{{ __('Define operational service types and premium pricing tiers.') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-4 md:px-10 max-w-[90rem] mx-auto space-y-12">
        
        {{-- Standardized Alert --}}
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-xl flex items-center gap-3 shadow-sm animate-fade-in">
                <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="text-[10px] font-medium text-emerald-700 uppercase tracking-widest">SYSTEM UPDATE: {{ session('success') }}</span>
            </div>
        @endif

        {{-- 1. CORE SERVICES SECTION --}}
        <div class="space-y-6">
            <div class="flex items-center justify-between px-1">
                <h3 class="text-[10px] font-medium uppercase tracking-[0.25em] text-slate-500">Core Service Catalog</h3>
                <span class="text-[10px] font-medium text-slate-400 uppercase tracking-widest">{{ $services->count() }} ACTIVE ENTRIES</span>
            </div>

            <div class="overflow-x-auto rounded-[2rem] shadow-2xl border border-slate-100 bg-white">
                <table class="w-full min-w-[800px]">
                    <x-table-header :headers="[['name'=>'DESIGNATION','width'=>'30%'], ['name'=>'UNIT COST','width'=>'20%'], ['name'=>'METRIC','width'=>'20%'], ['name'=>'AVAILABILITY','width'=>'15%'], ['name'=>'MANAGE','width'=>'15%']]" />

                    <tbody class="divide-y divide-slate-100">
                        @foreach($services as $service)
                        <tr x-data="{ editing: false }" class="hover:bg-slate-50 transition-all duration-200 text-center">
                            
                            <template x-if="!editing">
                                <td class="px-4 py-8">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="text-[11px] text-slate-900 uppercase tracking-tight">{{ $service->service_name }}</span>
                                        @if(!$service->is_active) <span class="text-[9px] text-rose-500 font-medium uppercase tracking-widest mt-1 italic">SYSTEM OFFLINE</span> @endif
                                    </div>
                                </td>
                            </template>
                            
                            <template x-if="!editing">
                                <td class="px-4 py-8">
                                    <span class="text-[11px] text-indigo-600 tracking-tighter">PHP {{ number_format($service->service_base_price, 2) }}</span>
                                </td>
                            </template>

                            <template x-if="!editing">
                                <td class="px-4 py-8">
                                    <span class="text-[10px] text-slate-500 uppercase tracking-widest">{{ str_replace('_', ' ', $service->pricing_type) }}</span>
                                </td>
                            </template>

                            <template x-if="!editing">
                                <td class="px-4 py-8 text-center">
                                    <form action="{{ route('admin.services.toggle', $service->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" 
                                            class="px-4 py-2 text-[9px] font-medium uppercase tracking-[0.2em] rounded-full border transition-all active:scale-90 shadow-sm
                                            {{ $service->is_active 
                                                ? 'bg-emerald-50 text-emerald-600 border-emerald-100 hover:bg-emerald-100' 
                                                : 'bg-rose-50 text-rose-600 border-rose-100 hover:bg-rose-100' }}">
                                            {{ $service->is_active ? 'ONLINE' : 'OFFLINE' }}
                                        </button>
                                    </form>
                                </td>
                            </template>

                            <template x-if="!editing">
                                <td class="px-4 py-8">
                                    <x-secondary-button @click="editing = true" class="!bg-[#475569] !text-white !border-none !text-[9px] !font-medium tracking-widest py-2 px-5 rounded-lg shadow-sm transition-all active:scale-95 uppercase">MODIFY</x-secondary-button>
                                </td>
                            </template>

                            {{-- Edit Mode Overlay --}}
                            <td x-show="editing" colspan="5" class="p-8 bg-slate-50" x-cloak>
                                <form action="{{ route('admin.services.update', $service->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-8 items-end bg-white p-8 rounded-[2rem] border border-slate-200 shadow-xl">
                                    @csrf @method('PATCH')
                                    <x-form-input label="Service Designation" name="service_name" :value="$service->service_name" required class="!font-medium uppercase !rounded-xl" />
                                    <x-form-input label="Unit Rate (PHP)" type="number" step="0.01" name="service_base_price" :value="$service->service_base_price" required class="!font-medium !rounded-xl" />
                                    
                                    <div class="space-y-2">
                                        <label class="block text-[10px] font-medium text-slate-400 uppercase tracking-widest ml-1">Metric Selection</label>
                                        <select name="pricing_type" class="w-full border-slate-200 rounded-xl p-3 text-[11px] font-medium text-slate-700 focus:ring-0 uppercase">
                                            <option value="PER_KG" {{ $service->pricing_type == 'PER_KG' ? 'selected' : '' }}>PER KILOGRAM</option>
                                            <option value="PER_ITEM" {{ $service->pricing_type == 'PER_ITEM' ? 'selected' : '' }}>PER ITEM</option>
                                        </select>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <x-primary-button class="!bg-indigo-600 !font-medium uppercase tracking-widest !rounded-full py-3 px-8 shadow-lg shadow-indigo-100">SAVE</x-primary-button>
                                        <button type="button" @click="editing = false" class="text-[10px] font-medium text-slate-400 hover:text-rose-500 uppercase tracking-widest px-2 transition-colors">DISCARD</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Add Service Section --}}
            <div class="bg-white rounded-[2.5rem] p-10 border border-slate-100 shadow-sm border-dashed border-2">
                <span class="block text-[10px] font-medium text-slate-400 uppercase tracking-[0.25em] mb-10 text-center italic">INITIALIZE NEW SERVICE CATEGORY</span>
                <form action="{{ route('admin.services.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-8 items-end">
                    @csrf
                    <x-form-input name="service_name" placeholder="IDENTIFY SERVICE TYPE" required class="!font-medium uppercase !rounded-xl" />
                    <x-form-input type="number" step="0.01" name="service_base_price" placeholder="RATE (0.00)" required class="!font-medium !rounded-xl" />
                    
                    <select name="pricing_type" class="w-full border-slate-200 rounded-xl p-3.5 text-[11px] font-medium text-slate-600 focus:ring-0 uppercase">
                        <option value="PER_KG">PER KILOGRAM</option>
                        <option value="PER_ITEM">PER ITEM</option>
                    </select>

                    <x-primary-button class="!bg-[#475569] !font-medium uppercase tracking-widest py-3.5 !rounded-full shadow-xl transition-all active:scale-95">
                        + ADD SERVICE
                    </x-primary-button>
                </form>
            </div>
        </div>

        {{-- 2. ADD-ONS SECTION --}}
        <div class="space-y-6 pt-8">
            <div class="flex items-center justify-between px-1">
                <h3 class="text-[10px] font-medium uppercase tracking-[0.25em] text-slate-500">Premium Utility Extras</h3>
                <span class="text-[10px] font-medium text-slate-400 uppercase tracking-widest">{{ $addons->count() }} OPTIONS</span>
            </div>

            <div class="overflow-x-auto rounded-[2rem] shadow-2xl border border-slate-100 bg-white">
                <table class="w-full min-w-[800px]">
                    <x-table-header :headers="[['name' => 'UTILITY','width' => '40%'], ['name' => 'PREMIUM COST','width' => '25%'], ['name' => 'AVAILABILITY','width' => '20%'], ['name' => 'MANAGE','width' => '15%']]" />

                    <tbody class="divide-y divide-slate-100">
                        @foreach($addons as $addon)
                        <tr x-data="{ editing: false }" class="hover:bg-slate-50 transition-all duration-200 text-center">
                            
                            <template x-if="!editing">
                                <td class="px-4 py-8">
                                    <span class="text-[11px] text-slate-900 uppercase tracking-tight">{{ $addon->addon_name }}</span>
                                </td>
                            </template>

                            <template x-if="!editing">
                                <td class="px-4 py-8">
                                    <span class="text-[11px] text-indigo-600 tracking-tighter">PHP {{ number_format($addon->addon_price, 2) }}</span>
                                </td>
                            </template>

                            <template x-if="!editing">
                                <td class="px-4 py-8">
                                    <form action="{{ route('admin.addons.toggle', $addon->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" 
                                            class="px-4 py-2 text-[9px] font-medium uppercase tracking-[0.2em] rounded-full border transition-all active:scale-90 shadow-sm
                                            {{ $addon->is_active 
                                                ? 'bg-emerald-50 text-emerald-600 border-emerald-100 hover:bg-emerald-100' 
                                                : 'bg-rose-50 text-rose-600 border-rose-100 hover:bg-rose-100' }}">
                                            {{ $addon->is_active ? 'ACTIVE' : 'INACTIVE' }}
                                        </button>
                                    </form>
                                </td>
                            </template>

                            <template x-if="!editing">
                                <td class="px-4 py-8">
                                    <x-secondary-button @click="editing = true" class="!bg-[#475569] !text-white !border-none !text-[9px] !font-medium tracking-widest py-2 px-5 rounded-lg shadow-sm transition-all active:scale-95 uppercase">MODIFY</x-secondary-button>
                                </td>
                            </template>

                            {{-- Edit Mode for Add-on --}}
                            <td x-show="editing" colspan="4" class="p-8 bg-slate-50" x-cloak>
                                <form action="{{ route('admin.addons.update', $addon->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-8 items-end bg-white p-8 rounded-[2rem] border border-slate-200 shadow-xl">
                                    @csrf @method('PATCH')
                                    <x-form-input label="Extra Designation" name="addon_name" :value="$addon->addon_name" required class="!font-medium uppercase !rounded-xl" />
                                    <x-form-input label="Premium Rate" type="number" step="0.01" name="addon_price" :value="$addon->addon_price" required class="!font-medium !rounded-xl" />
                                    
                                    <div class="flex items-center gap-4">
                                        <x-primary-button class="!bg-indigo-600 !font-medium uppercase tracking-widest !rounded-full py-3 px-8 shadow-lg shadow-indigo-100 w-full sm:w-auto">SAVE</x-primary-button>
                                        <button type="button" @click="editing = false" class="text-[10px] font-medium text-slate-400 hover:text-rose-500 uppercase tracking-widest transition-colors">DISCARD</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Add Add-on --}}
            <div class="bg-white rounded-[2.5rem] p-10 border border-slate-100 shadow-sm border-dashed border-2">
                <span class="block text-[10px] font-medium text-slate-400 uppercase tracking-[0.25em] mb-10 text-center italic">INITIALIZE NEW PREMIUM OPTION</span>
                <form action="{{ route('admin.addons.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-8 items-end">
                    @csrf
                    <x-form-input name="addon_name" placeholder="DESCRIBE UTILITY EXTRA" required class="!font-medium uppercase !rounded-xl" />
                    <x-form-input type="number" step="0.01" name="addon_price" placeholder="RATE (0.00)" required class="!font-medium !rounded-xl" />
                    <x-primary-button class="!bg-[#475569] !font-medium uppercase tracking-widest py-3.5 !rounded-full shadow-xl transition-all active:scale-95">
                        + ADD ONS
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>