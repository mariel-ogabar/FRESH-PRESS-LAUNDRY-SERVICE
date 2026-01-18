<x-app-layout>
    <div x-data="{ 
        showLookup: {{ auth()->user()->can('create orders') && !request('email') ? 'true' : 'false' }},
        basePrice: 0,
        loadSize: 0,
        addonsTotal: 0,
        serviceName: '',
        unit: 'kg',
        selectedAddons: [],
        
        colMethod: 'DROP_OFF',
        retMethod: 'PICKUP',

        // New Logic State to track database values
        userFound: {{ $foundUser ? 'true' : 'false' }},
        hasContact: {{ ($foundUser && $foundUser->contact_no) || (auth()->user()->hasRole('CUSTOMER') && auth()->user()->contact_no) ? 'true' : 'false' }},
        hasAddress: {{ ($foundUser && $foundUser->address) || (auth()->user()->hasRole('CUSTOMER') && auth()->user()->address) ? 'true' : 'false' }},

        get needsLogistics() {
            return this.colMethod === 'STAFF_PICKUP' || this.retMethod === 'DELIVERY';
        },

        // Logic: Show Contact input if user is brand new OR if logistics are needed but contact is missing
        get showContactInput() {
            if (!this.userFound && !{{ auth()->user()->hasRole('CUSTOMER') ? 'true' : 'false' }}) return true;
            if (this.needsLogistics && !this.hasContact) return true;
            return false;
        },

        // Logic: Show Address input if user is brand new OR if logistics are needed but address is missing
        get showAddressInput() {
            if (!this.userFound && !{{ auth()->user()->hasRole('CUSTOMER') ? 'true' : 'false' }}) return true;
            if (this.needsLogistics && !this.hasAddress) return true;
            return false;
        },

        updateBasePrice(el) {
            const selected = el.options[el.selectedIndex];
            this.basePrice = selected.dataset.price ? parseFloat(selected.dataset.price) : 0;
            this.serviceName = selected.text.split(' (')[0];
            this.unit = selected.text.includes('/kg') ? 'kg' : 'pc/s';
        },

        updateAddons() {
            let total = 0;
            this.selectedAddons = [];
            document.querySelectorAll('input[name=\'addons[]\']:checked').forEach(el => {
                total += parseFloat(el.dataset.price);
                this.selectedAddons.push(el.dataset.name);
            });
            this.addonsTotal = total;
        },

        get totalPrice() {
            return (this.basePrice * this.loadSize) + this.addonsTotal;
        }
    }">

        {{-- Validation Error Display --}}
        @if ($errors->any())
            <div style="border: 1px solid red; background: #fee2e2; color: red; padding: 15px; margin-bottom: 20px;">
                <strong>Please fix the following:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Walk-in Check Modal --}}
        <template x-if="showLookup">
            <div style="background: rgba(0,0,0,0.5); position: fixed; inset: 0; display: flex; align-items: center; justify-content: center; z-index: 100;">
                <div style="background: white; padding: 20px; border: 1px solid #000;">
                    <h2>Walk-in Check</h2>
                    <form action="{{ route('orders.create') }}" method="GET">
                        <input type="email" name="email" required placeholder="customer@email.com">
                        <button type="submit">Verify Customer</button>
                    </form>
                </div>
            </div>
        </template>

        <h1>Book a Service</h1>
        <p>@hasanyrole('ADMIN|STAFF') {{ request('email') ? 'Processing: ' . request('email') : 'Preparing new walk-in order.' }} @else Schedule your laundry. @endhasanyrole</p>

        <form action="{{ route('orders.store') }}" method="POST">
            @csrf

            <fieldset>
                <legend>Customer Info</legend>
                @hasrole('CUSTOMER')
                    <p>Name: {{ auth()->user()->name }}</p>
                    <p>Email: {{ auth()->user()->email }}</p>
                    
                    {{-- Hidden inputs to pass existing data if fields are hidden --}}
                    <div x-show="hasContact && !showContactInput">
                        <p>Contact: {{ auth()->user()->contact_no }}</p>
                        <input type="hidden" name="contact_no" value="{{ auth()->user()->contact_no }}">
                    </div>
                    <div x-show="hasAddress && !showAddressInput">
                        <p>Address: {{ auth()->user()->address }}</p>
                        <input type="hidden" name="address" value="{{ auth()->user()->address }}">
                    </div>

                    {{-- Form for missing or required logistics data --}}
                    <div x-show="showContactInput">
                        <label>Contact #:</label>
                        <input type="text" name="contact_no" :required="showContactInput" placeholder="09123456789" class="border-black">
                    </div>
                    <div x-show="showAddressInput">
                        <label>Address:</label>
                        <input type="text" name="address" :required="showAddressInput" placeholder="Street, City, Province" class="border-black">
                    </div>
                @else
                    {{-- Admin/Staff Walk-in Logic --}}
                    <input type="hidden" name="email" value="{{ request('email') }}">
                    <p>Processing: <strong>{{ request('email') }}</strong> 
                        @can('create orders')
                            [<a href="{{ route('orders.create') }}" class="text-blue-600 underline">CHANGE</a>]
                        @endcan
                    </p>

                    @if($foundUser)
                        <p>Customer Found: <strong>{{ $foundUser->name }}</strong></p>
                        <input type="hidden" name="customer_name" value="{{ $foundUser->name }}">
                        
                        <div x-show="hasContact && !showContactInput">
                            <p>Contact: {{ $foundUser->contact_no }}</p>
                            <input type="hidden" name="contact_no" value="{{ $foundUser->contact_no }}">
                        </div>
                        <div x-show="hasAddress && !showAddressInput">
                            <p>Address: {{ $foundUser->address }}</p>
                            <input type="hidden" name="address" value="{{ $foundUser->address }}">
                        </div>

                        <div x-show="showContactInput">
                            <label>Contact #:</label>
                            <input type="text" name="contact_no" value="{{ old('contact_no', $foundUser->contact_no) }}" :required="showContactInput" class="border-black">
                        </div>
                        <div x-show="showAddressInput">
                            <label>Address:</label>
                            <input type="text" name="address" value="{{ old('address', $foundUser->address) }}" :required="showAddressInput" class="border-black">
                        </div>
                    @else
                        {{-- Completely New Customer --}}
                        <label>Full Name:</label>
                        <input type="text" name="customer_name" required placeholder="Enter Name" class="border-black"><br>
                        <label>Contact #:</label>
                        <input type="text" name="contact_no" :required="needsLogistics" placeholder="Enter Contact Number" class="border-black"><br>
                        <label>Address:</label>
                        <input type="text" name="address" :required="needsLogistics" placeholder="Enter Address" class="border-black">
                    @endif
                @endhasrole
            </fieldset>

            <fieldset>
                <legend>Service Details</legend>
                <select name="service_id" required @change="updateBasePrice($el)">
                    <option value="">Select Service Type</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" data-price="{{ $service->service_base_price }}">
                            {{ $service->service_name }} (₱{{ number_format($service->service_base_price, 2) }}/{{ $service->pricing_type === 'PER_KG' ? 'kg' : 'pc' }})
                        </option>
                    @endforeach
                </select>
                <br>
                <label>Quantity/Weight:</label>
                <input type="number" name="load_size" step="0.1" required x-model.number="loadSize">
            </fieldset>

            <fieldset>
                <legend>Optional Add-ons</legend>
                @foreach($addons as $addon)
                    <div>
                        <input type="checkbox" name="addons[]" value="{{ $addon->id }}" 
                               data-price="{{ $addon->addon_price }}" 
                               data-name="{{ $addon->addon_name }}" 
                               @change="updateAddons()">
                        {{ $addon->addon_name }} (+₱{{ number_format($addon->addon_price, 2) }})
                    </div>
                @endforeach
            </fieldset>

            <fieldset>
                <legend>Collection & Return</legend>
                <label>Collection:</label>
                <select name="collection_method" x-model="colMethod">
                    <option value="DROP_OFF">Store Drop-off</option>
                    <option value="STAFF_PICKUP">Staff Pickup</option>
                </select>

                <div x-show="colMethod === 'STAFF_PICKUP'">
                    <input type="date" name="collection_date" :required="colMethod === 'STAFF_PICKUP'">
                    <input type="time" name="collection_time" :required="colMethod === 'STAFF_PICKUP'">
                </div>
                <br>
                <label>Return:</label>
                <select name="return_method" x-model="retMethod">
                    <option value="PICKUP">Store Pickup</option>
                    <option value="DELIVERY">Home Delivery</option>
                </select>
            </fieldset>

            <p><strong>Payment: Cash on Delivery (COD) only</strong></p>

            <div style="border: 2px solid black; padding: 15px; margin-top: 10px;">
                <h3>Summary</h3>
                <p>Service: <span x-text="serviceName || 'None'"></span></p>
                <p x-show="loadSize > 0">Total Size: <span x-text="loadSize"></span> <span x-text="unit"></span></p>
                
                <div x-show="selectedAddons.length > 0">
                    <p><strong>Add-ons:</strong></p>
                    <ul style="margin: 0; padding-left: 20px;">
                        <template x-for="addon in selectedAddons">
                            <li x-text="addon"></li>
                        </template>
                    </ul>
                </div>

                {{-- Alert for missing info when logistics are selected --}}
                <div x-show="needsLogistics && (!hasContact || !hasAddress)" style="color: red; font-size: 0.85em; margin-bottom: 10px; font-weight: bold;">
                    ⚠ Missing Details: Please provide contact and address above for logistics processing.
                </div>

                <h2>Total: <span x-text="'₱' + totalPrice.toLocaleString(undefined, {minimumFractionDigits: 2})">₱0.00</span></h2>
                
                <button type="submit" style="padding: 10px 20px; font-weight: bold; background: black; color: white; cursor: pointer;">
                    CONFIRM BOOKING
                </button>
            </div>
        </form>
    </div>
</x-app-layout>