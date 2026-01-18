<x-app-layout>
    <div x-data="{ 
        showLookup: {{ auth()->user()->can('create orders') && !request('email') ? 'true' : 'false' }},
        basePrice: 0, loadSize: 0, addonsTotal: 0, serviceName: '', unit: 'kg', selectedAddons: [],
        colMethod: 'DROP_OFF', retMethod: 'PICKUP', userFound: {{ $foundUser ? 'true' : 'false' }},
        hasContact: {{ ($foundUser && $foundUser->contact_no) || (auth()->user()->hasRole('CUSTOMER') && auth()->user()->contact_no) ? 'true' : 'false' }},
        hasAddress: {{ ($foundUser && $foundUser->address) || (auth()->user()->hasRole('CUSTOMER') && auth()->user()->address) ? 'true' : 'false' }},
        
        get needsLogistics() { return this.colMethod === 'STAFF_PICKUP' || this.retMethod === 'DELIVERY'; },
        get showContactInput() { return (!this.userFound && !{{ auth()->user()->hasRole('CUSTOMER') ? 'true' : 'false' }}) || (this.needsLogistics && !this.hasContact); },
        get showAddressInput() { return (!this.userFound && !{{ auth()->user()->hasRole('CUSTOMER') ? 'true' : 'false' }}) || (this.needsLogistics && !this.hasAddress); },
        updateBasePrice(el) {
            const selected = el.options[el.selectedIndex];
            this.basePrice = selected.dataset.price ? parseFloat(selected.dataset.price) : 0;
            this.serviceName = selected.text.split(' (')[0];
            this.unit = selected.text.includes('/kg') ? 'kg' : 'pc/s';
        },
        updateAddons() {
            let total = 0; this.selectedAddons = [];
            document.querySelectorAll('input[name=\'addons[]\']:checked').forEach(el => {
                total += parseFloat(el.dataset.price);
                this.selectedAddons.push(el.dataset.name);
            });
            this.addonsTotal = total;
        },
        get totalPrice() { return (this.basePrice * (this.loadSize || 0)) + this.addonsTotal; }
    }">

        <style>[x-cloak] { display: none !important; }</style>

        <x-slot name="header">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-2 md:px-4">
                <div>
                    <h2 class="font-medium text-xl text-slate-800 uppercase tracking-tighter">{{ __('Add Walk-In Order') }}</h2>
                    <p class="text-[11px] font-medium text-slate-500 uppercase tracking-widest mt-1">
                        @hasanyrole('ADMIN|STAFF') {{ request('email') ? 'Processing: ' . request('email') : 'Preparing new walk-in order.' }} @else Schedule your laundry service. @endhasanyrole
                    </p>
                </div>
                
                @hasanyrole('ADMIN|STAFF')
                    @if(request('email'))
                        <x-primary-button onclick="window.location='{{ route('orders.create') }}'" 
                        class="!bg-[#475569] hover:!bg-[#334155] !text-white !font-medium px-6 py-2.5 rounded-full shadow-lg transition-all active:scale-95 text-center justify-center uppercase">
                            {{ __('Change Target Email') }}
                        </x-primary-button>
                    @endif
                @endhasanyrole
            </div>
        </x-slot>

        <div class="py-8 px-4 md:px-10 max-w-[90rem] mx-auto">
            
            {{-- Walk-in Verification Modal --}}
            <div x-show="showLookup" x-cloak class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[40] flex items-center justify-center p-4">
                <div class="bg-white rounded-[2rem] shadow-2xl border border-slate-100 w-full max-w-lg overflow-hidden animate-fade-in">
                    <div class="bg-slate-50/80 px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-medium text-slate-800 uppercase tracking-tighter">Walk-in Verification</h2>
                            <p class="text-[10px] font-medium text-slate-400 uppercase tracking-widest mt-1">Identity Check</p>
                        </div>
                    </div>
                    <div class="p-10">
                        <form action="{{ route('orders.create') }}" method="GET" class="space-y-8">
                            <x-form-input label="Customer Email Identifier" name="email" required placeholder="CUSTOMER@EMAIL.COM" class="!rounded-xl uppercase shadow-sm font-medium" />
                            <div class="pt-6 border-t border-slate-50 flex items-center justify-end">
                                 <x-primary-button class="!bg-[#475569] !rounded-full !py-3 !px-10 !text-[10px] !font-medium tracking-[0.2em]">VERIFY IDENTITY</x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <form action="{{ route('orders.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                @csrf
                <div class="lg:col-span-8 space-y-12">
                    
                    {{-- Customer Profile --}}
                    <section class="bg-white rounded-[2.5rem] border border-slate-100 shadow-2xl overflow-hidden">
                        <div class="bg-slate-50/50 px-8 py-5 border-b border-slate-100 text-center">
                            <span class="text-[10px] font-medium text-slate-400 uppercase tracking-[0.25em]">Customer Profile Logic</span>
                        </div>
                        <div class="p-10 space-y-8">
                            @hasrole('CUSTOMER')
                                <input type="hidden" name="contact_no" value="{{ auth()->user()->contact_no }}">
                                <input type="hidden" name="address" value="{{ auth()->user()->address }}">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 font-medium uppercase tracking-widest text-[11px]">
                                    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100">
                                        <span class="text-[9px] text-slate-400 font-medium block mb-1">Authenticated Identity</span>
                                        <span class="text-slate-800">{{ auth()->user()->name }}</span>
                                    </div>
                                    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100">
                                        <span class="text-[9px] text-slate-400 font-medium block mb-1">System Email</span>
                                        <span class="text-slate-800 tracking-normal lowercase">{{ auth()->user()->email }}</span>
                                    </div>
                                </div>
                            @else
                                <input type="hidden" name="email" value="{{ request('email') }}">
                                @if($foundUser)
                                    <input type="hidden" name="contact_no" value="{{ $foundUser->contact_no }}">
                                    <input type="hidden" name="address" value="{{ $foundUser->address }}">
                                    <div class="bg-indigo-50 border border-indigo-100 p-6 rounded-[1.5rem] mb-6 flex justify-between items-center shadow-sm">
                                        <div>
                                            <span class="text-[9px] text-indigo-400 uppercase tracking-widest block font-medium">Customer Identified</span>
                                            <span class="text-sm font-medium text-indigo-700 uppercase">{{ $foundUser->name }}</span>
                                            <input type="hidden" name="customer_name" value="{{ $foundUser->name }}">
                                        </div>
                                    </div>
                                @else
                                    <div class="grid grid-cols-1 gap-6 mb-6">
                                        <x-form-input label="Full Name" name="customer_name" required placeholder="ENTER FULL NAME" class="!rounded-xl uppercase shadow-sm font-medium" />
                                    </div>
                                @endif
                            @endhasrole

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div x-show="showContactInput" class="animate-fade-in"><x-form-input label="Contact No." name="contact_no" value="{{ old('contact_no', $foundUser->contact_no ?? '') }}" ::required="showContactInput" class="!rounded-xl shadow-sm font-medium" /></div>
                                <div x-show="showAddressInput" class="animate-fade-in"><x-form-input label="Service Address" name="address" value="{{ old('address', $foundUser->address ?? '') }}" ::required="showAddressInput" class="!rounded-xl shadow-sm font-medium" /></div>
                            </div>
                        </div>
                    </section>

                    {{-- Service Specs --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <section class="bg-white rounded-[2.5rem] border border-slate-100 shadow-2xl overflow-hidden">
                            <div class="bg-slate-50/50 px-8 py-5 border-b border-slate-100 text-center"><span class="text-[10px] font-medium text-slate-400 uppercase tracking-[0.25em]">Service Specification</span></div>
                            <div class="p-10 space-y-8">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-medium text-slate-400 uppercase tracking-widest ml-1 block">Active Category</label>
                                    <select name="service_id" required @change="updateBasePrice($el)" class="w-full border-slate-200 rounded-xl p-4 text-sm uppercase text-slate-700 focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all shadow-sm font-medium">
                                        <option value="">Select Service Type</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}" data-price="{{ $service->service_base_price }}">{{ $service->service_name }} (₱{{ number_format($service->service_base_price, 2) }}/{{ $service->pricing_type === 'PER_KG' ? 'kg' : 'pc' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-medium text-slate-400 uppercase tracking-widest ml-1 block">
                                        Item / Kilogram
                                    </label>
                                    <x-form-input 
                                        type="number" 
                                        step="0.1" 
                                        name="load_size" 
                                        required 
                                        x-model.number="loadSize" 
                                        class="!rounded-xl shadow-sm font-medium !text-slate-700" 
                                    />
                                </div>
                            </div>
                        </section>

                        <section class="bg-white rounded-[2.5rem] border border-slate-100 shadow-2xl overflow-hidden">
                            <div class="bg-slate-50/50 px-8 py-5 border-b border-slate-100 text-center"><span class="text-[10px] font-medium text-slate-400 uppercase tracking-[0.25em]">Premium Modifiers</span></div>
                            <div class="p-10 space-y-4 max-h-[260px] overflow-y-auto custom-scrollbar">
                                @foreach($addons as $addon)
                                    <label class="flex items-center justify-between p-4 rounded-2xl border border-slate-50 hover:bg-slate-50 transition-all cursor-pointer group">
                                        <div class="flex items-center gap-4 font-medium">
                                            <input type="checkbox" name="addons[]" value="{{ $addon->id }}" data-price="{{ $addon->addon_price }}" data-name="{{ $addon->addon_name }}" @change="updateAddons()" class="w-5 h-5 text-indigo-600 border-slate-300 rounded-lg focus:ring-0 transition-all cursor-pointer shadow-sm">
                                            <span class="text-[11px] text-slate-600 uppercase tracking-widest italic">{{ $addon->addon_name }}</span>
                                        </div>
                                        <span class="text-[10px] font-medium text-indigo-500 tracking-tighter">₱{{ number_format($addon->addon_price, 2) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </section>
                    </div>

                    <section class="bg-white rounded-[2.5rem] border border-slate-100 shadow-2xl overflow-hidden">
                        <div class="bg-slate-50/50 px-8 py-5 border-b border-slate-100 text-center"><span class="text-[10px] font-medium text-slate-400 uppercase tracking-[0.25em]">Logistical Scheduling</span></div>
                        <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-12">
                            <div class="space-y-6">
                                <label class="text-[10px] font-medium text-slate-400 uppercase tracking-widest ml-1 block">Collection</label>
                                <select name="collection_method" x-model="colMethod" class="w-full border-slate-200 rounded-xl p-4 text-sm uppercase text-slate-700 shadow-sm font-medium">
                                    <option value="DROP_OFF">Store Drop-off</option>
                                    <option value="STAFF_PICKUP">Staff Pickup</option>
                                </select>
                                <div x-show="colMethod === 'STAFF_PICKUP'" x-cloak class="grid grid-cols-2 gap-4 animate-fade-in pt-2 font-medium">
                                    <input type="date" name="collection_date" ::required="colMethod === 'STAFF_PICKUP'" class="w-full border-slate-200 rounded-xl p-3 text-sm text-slate-600 focus:ring-0 shadow-sm font-medium">
                                    <input type="time" name="collection_time" ::required="colMethod === 'STAFF_PICKUP'" class="w-full border-slate-200 rounded-xl p-3 text-sm text-slate-600 focus:ring-0 shadow-sm font-medium">
                                </div>
                            </div>
                            <div class="space-y-6">
                                <label class="text-[10px] font-medium text-slate-400 uppercase tracking-widest ml-1 block">Return</label>
                                <select name="return_method" x-model="retMethod" class="w-full border-slate-200 rounded-xl p-4 text-sm uppercase text-slate-700 shadow-sm font-medium">
                                    <option value="PICKUP">Store Pickup</option>
                                    <option value="DELIVERY">Delivery</option>
                                </select>
                            </div>
                        </div>
                    </section>
                </div>

                {{-- Sidebar Summary --}}
                <div class="lg:col-span-4">
                    <div class="sticky top-8 space-y-6">
                        <section class="bg-white rounded-[3rem] p-10 shadow-2xl relative overflow-hidden border border-slate-100">
                            <h3 class="text-[10px] font-medium uppercase tracking-[0.4em] text-slate-400 mb-10 border-b border-slate-50 pb-6 text-center">Operational Summary</h3>
                            <div class="space-y-6 mb-12">
                                <div class="flex justify-between items-center text-[10px] uppercase tracking-[0.2em] font-medium"><span class="text-slate-400 italic font-medium">Primary:</span><span x-text="serviceName || '—'" class="text-slate-800"></span></div>
                                <div x-show="loadSize > 0" class="flex justify-between items-center border-t border-slate-50 pt-6 text-[10px] uppercase tracking-[0.2em] font-medium"><span class="text-slate-400 italic font-medium">Config:</span><span class="text-slate-800"><span x-text="loadSize"></span> <span x-text="unit"></span></span></div>
                                <div x-show="selectedAddons.length > 0" class="border-t border-slate-50 pt-6 space-y-3 font-medium">
                                    <span class="text-[9px] text-indigo-500 uppercase tracking-widest block font-medium">Premium Extras:</span>
                                    <template x-for="addon in selectedAddons"><div class="text-[10px] text-slate-500 tracking-tighter uppercase flex justify-between font-medium"><span x-text="addon"></span><span class="text-slate-300">+INC</span></div></template>
                                </div>
                            </div>

                            <div x-show="needsLogistics && (!hasContact || !hasAddress)" x-cloak class="bg-rose-50 border border-rose-100 p-4 rounded-2xl text-[9px] font-medium text-rose-500 uppercase tracking-widest mb-8 italic text-center animate-pulse">Profile Data Incomplete</div>

                            <div class="text-center mb-10 space-y-2">
                                <span class="text-[10px] font-medium text-slate-400 uppercase tracking-[0.2em] block">Calculated Total (COD)</span>
                                <div class="text-6xl font-medium tracking-tighter text-slate-900 font-medium" x-text="'₱' + totalPrice.toLocaleString(undefined, {minimumFractionDigits: 2})">₱0.00</div>
                            </div>

                            <div class="flex justify-center pt-2">
                                <x-secondary-button type="submit" class="!bg-[#475569] hover:!bg-[#334155] !text-white !border-none !text-[9px] !font-medium tracking-widest py-3 px-8 rounded-lg shadow-sm transition-all active:scale-95 uppercase">
                                    Confirm Booking
                                </x-secondary-button>
                            </div>
                        </section>
                        
                        <div class="bg-slate-50 p-6 rounded-[2rem] border border-slate-100 text-center shadow-sm font-medium uppercase tracking-widest text-[9px] text-slate-400 italic">
                             Cash on Delivery
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>