<x-app-layout>
    <div x-data="{ 
        showLookup: {{ auth()->user()->role === 'ADMIN' && !request('email') ? 'true' : 'false' }},
        basePrice: 0,
        loadSize: 0,
        addonsTotal: 0,
        serviceName: '',
        unit: 'kg',

        updateBasePrice(el) {
            const selected = el.options[el.selectedIndex];
            this.basePrice = selected.dataset.price ? parseFloat(selected.dataset.price) : 0;
            this.serviceName = selected.text.split(' (')[0];
            this.unit = selected.text.includes('/kg') ? 'kg' : 'pc/s';
        },

        updateAddons() {
            let total = 0;
            document.querySelectorAll('input[name=\'addons[]\']:checked').forEach(el => {
                total += parseFloat(el.dataset.price);
            });
            this.addonsTotal = total;
        },

        get totalPrice() {
            return (this.basePrice * this.loadSize) + this.addonsTotal;
        }
    }">

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
        <p>{{ auth()->user()->role === 'ADMIN' ? 'Processing: ' . request('email') : 'Schedule your laundry.' }}</p>

        <form action="{{ route('orders.store') }}" method="POST">
            @csrf

            <fieldset>
                <legend>Customer Info</legend>
                @if(auth()->user()->role === 'CUSTOMER')
                    <p>Name: {{ auth()->user()->name }}</p>
                    <p>Email: {{ auth()->user()->email }}</p>
                @else
                    <input type="hidden" name="email" value="{{ request('email') }}">
                    
                    <p>
                        Processing: <strong>{{ request('email') }}</strong> 
                        [<a href="{{ route('orders.create') }}">CHANGE</a>]
                    </p>

                    @if($foundUser)
                        <p>Customer Found: <strong>{{ $foundUser->name }}</strong></p>
                        <input type="hidden" name="customer_name" value="{{ $foundUser->name }}">
                    @else
                        <p><em>New Customer detected. Please fill in details:</em></p>
                        <label>Full Name:</label>
                        <input type="text" name="customer_name" required placeholder="Enter Name"><br>
                        
                        <label>Contact #:</label>
                        <input type="text" name="contact_no" required placeholder="Enter Contact Number"><br>
                        
                        <label>Address:</label>
                        <input type="text" name="address" required placeholder="Enter Address">
                    @endif
                @endif
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
                               data-price="{{ $addon->addon_price }}" @change="updateAddons()">
                        {{ $addon->addon_name }} (+₱{{ number_format($addon->addon_price, 2) }})
                    </div>
                @endforeach
            </fieldset>

            <fieldset x-data="{ method: 'DROP_OFF' }">
                <legend>Collection & Return</legend>
                <label>Collection:</label>
                <select name="collection_method" x-model="method">
                    <option value="DROP_OFF">Store Drop-off</option>
                    <option value="STAFF_PICKUP">Staff Pickup</option>
                </select>

                <div x-show="method === 'STAFF_PICKUP'">
                    <input type="date" name="collection_date">
                    <input type="time" name="collection_time">
                </div>
                <br>
                <label>Return:</label>
                <select name="return_method">
                    <option value="PICKUP">Store Pickup</option>
                    <option value="DELIVERY">Home Delivery</option>
                </select>
            </fieldset>

            <p><strong>Payment: Cash on Delivery (COD) only</strong></p>

            <div style="border: 2px solid black; padding: 15px; margin-top: 10px;">
                <h3>Summary</h3>
                <p>Service: <span x-text="serviceName || 'None'"></span></p>
                <p x-show="loadSize > 0">Total Size: <span x-text="loadSize"></span> <span x-text="unit"></span></p>
                <h2>Total: <span x-text="'₱' + totalPrice.toLocaleString(undefined, {minimumFractionDigits: 2})">₱0.00</span></h2>
                
                <button type="submit" style="padding: 10px 20px; font-weight: bold;">
                    CONFIRM BOOKING
                </button>
            </div>

        </form>
    </div>
</x-app-layout>