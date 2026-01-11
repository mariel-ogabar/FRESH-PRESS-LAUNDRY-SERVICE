<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Service & Pricing Management') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div style="border: 1px solid black; padding: 10px; margin-bottom: 20px; background-color: #f0fff4;">
                    <strong>SUCCESS:</strong> {{ session('success') }}
                </div>
            @endif

            {{-- MAIN SERVICES SECTION --}}
            <div style="background: white; border: 1px solid black; padding: 25px;">
                <h3 style="font-weight: bold; margin-bottom: 15px;">MAIN SERVICES</h3>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                    <thead>
                        <tr style="background: #f3f3f3;">
                            <th style="border: 1px solid black; padding: 10px; text-align: left;">SERVICE NAME</th>
                            <th style="border: 1px solid black; padding: 10px; text-align: left;">PRICE</th>
                            <th style="border: 1px solid black; padding: 10px; text-align: left;">UNIT</th>
                            <th style="border: 1px solid black; padding: 10px; text-align: center;">STATUS</th>
                            <th style="border: 1px solid black; padding: 10px; text-align: right;">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($services as $service)
                        <tr x-data="{ editing: false }" style="border-bottom: 1px solid black;">
                            {{-- View Mode --}}
                            <td x-show="!editing" style="border: 1px solid black; padding: 10px;">{{ $service->service_name }}</td>
                            <td x-show="!editing" style="border: 1px solid black; padding: 10px;">₱{{ number_format($service->service_base_price, 2) }}</td>
                            <td x-show="!editing" style="border: 1px solid black; padding: 10px;">{{ $service->pricing_type }}</td>
                            <td x-show="!editing" style="border: 1px solid black; padding: 10px; text-align: center;">
                                <form action="{{ route('admin.services.toggle', $service->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" style="font-weight: bold; cursor: pointer; background: none; border: none; color: {{ $service->is_active ? 'green' : 'red' }}; text-decoration: underline;">
                                        {{ $service->is_active ? 'ACTIVE' : 'INACTIVE' }}
                                    </button>
                                </form>
                            </td>
                            <td x-show="!editing" style="border: 1px solid black; padding: 10px; text-align: right;">
                                <button @click="editing = true" style="text-decoration: underline; font-weight: bold; margin-right: 10px; cursor: pointer;">EDIT</button>
                                <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="text-decoration: underline; background: none; border: none; cursor: pointer; color: red;">REMOVE</button>
                                </form>
                            </td>

                            {{-- Edit Mode --}}
                            <td x-show="editing" colspan="5" style="border: 1px solid black; padding: 10px; background-color: #f9f9f9;">
                                <form action="{{ route('admin.services.update', $service->id) }}" method="POST" style="display: flex; gap: 10px; align-items: center;">
                                    @csrf @method('PATCH')
                                    <input type="text" name="service_name" value="{{ $service->service_name }}" required style="flex: 2; border: 1px solid black; padding: 5px;">
                                    <input type="number" step="0.01" name="service_base_price" value="{{ $service->service_base_price }}" required style="flex: 1; border: 1px solid black; padding: 5px;">
                                    <select name="pricing_type" style="flex: 1; border: 1px solid black; padding: 5px;">
                                        <option value="PER_KG" {{ $service->pricing_type == 'PER_KG' ? 'selected' : '' }}>PER KG</option>
                                        <option value="PER_ITEM" {{ $service->pricing_type == 'PER_ITEM' ? 'selected' : '' }}>PER ITEM</option>
                                    </select>
                                    <button type="submit" style="background: black; color: white; padding: 5px 15px; font-weight: bold; border: none; cursor: pointer;">SAVE</button>
                                    <button type="button" @click="editing = false" style="text-decoration: underline; cursor: pointer;">CANCEL</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Add Service Form --}}
                <form action="{{ route('admin.services.store') }}" method="POST" style="display: flex; gap: 10px;">
                    @csrf
                    <input type="text" name="service_name" placeholder="New Service Name" required style="flex: 2; border: 1px solid black; padding: 8px;">
                    <input type="number" step="0.01" name="service_base_price" placeholder="Price" required style="flex: 1; border: 1px solid black; padding: 8px;">
                    <select name="pricing_type" style="flex: 1; border: 1px solid black; padding: 8px;">
                        <option value="PER_KG">PER KG</option>
                        <option value="PER_ITEM">PER ITEM</option>
                    </select>
                    <button type="submit" style="background: black; color: white; padding: 8px 20px; font-weight: bold; border: none; cursor: pointer;">ADD SERVICE</button>
                </form>
            </div>

            {{-- ADD-ONS SECTION --}}
            <div style="background: white; border: 1px solid black; padding: 25px;">
                <h3 style="font-weight: bold; margin-bottom: 15px;">ADD-ONS</h3>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                    <thead>
                        <tr style="background: #f3f3f3;">
                            <th style="border: 1px solid black; padding: 10px; text-align: left;">ADD-ON NAME</th>
                            <th style="border: 1px solid black; padding: 10px; text-align: left;">PRICE</th>
                            <th style="border: 1px solid black; padding: 10px; text-align: right;">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($addons as $addon)
                        <tr x-data="{ editing: false }" style="border-bottom: 1px solid black;">
                            {{-- View Mode --}}
                            <td x-show="!editing" style="border: 1px solid black; padding: 10px;">{{ $addon->addon_name }}</td>
                            <td x-show="!editing" style="border: 1px solid black; padding: 10px;">₱{{ number_format($addon->addon_price, 2) }}</td>
                            <td x-show="!editing" style="border: 1px solid black; padding: 10px; text-align: right;">
                                <button @click="editing = true" style="text-decoration: underline; font-weight: bold; margin-right: 10px; cursor: pointer;">EDIT</button>
                                <form action="{{ route('admin.addons.destroy', $addon->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Remove this add-on?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="text-decoration: underline; background: none; border: none; cursor: pointer; color: red;">REMOVE</button>
                                </form>
                            </td>

                            {{-- Edit Mode --}}
                            <td x-show="editing" colspan="3" style="border: 1px solid black; padding: 10px; background-color: #f9f9f9;">
                                <form action="{{ route('admin.addons.update', $addon->id) }}" method="POST" style="display: flex; gap: 10px; align-items: center;">
                                    @csrf @method('PATCH')
                                    <input type="text" name="addon_name" value="{{ $addon->addon_name }}" required style="flex: 2; border: 1px solid black; padding: 5px;">
                                    <input type="number" step="0.01" name="addon_price" value="{{ $addon->addon_price }}" required style="flex: 1; border: 1px solid black; padding: 5px;">
                                    <button type="submit" style="background: black; color: white; padding: 5px 15px; font-weight: bold; border: none; cursor: pointer;">SAVE</button>
                                    <button type="button" @click="editing = false" style="text-decoration: underline; cursor: pointer;">CANCEL</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Add Add-on Form --}}
                <form action="{{ route('admin.addons.store') }}" method="POST" style="display: flex; gap: 10px;">
                    @csrf
                    <input type="text" name="addon_name" placeholder="New Add-on Name" required style="flex: 2; border: 1px solid black; padding: 8px;">
                    <input type="number" step="0.01" name="addon_price" placeholder="Price" required style="flex: 1; border: 1px solid black; padding: 8px;">
                    <button type="submit" style="background: black; color: white; padding: 8px 20px; font-weight: bold; border: none; cursor: pointer;">ADD ADD-ON</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>