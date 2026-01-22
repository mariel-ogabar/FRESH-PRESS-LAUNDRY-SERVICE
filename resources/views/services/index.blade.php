<x-app-layout>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc; 
            color: #334155; 
            -webkit-font-smoothing: antialiased;
        }
        .service-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #f1f5f9;
        }
        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 30px 60px -12px rgba(124, 77, 255, 0.12);
            border-color: #7c4dff33;
        }
        .weight-box {
            transition: all 0.2s ease;
        }
        .weight-box:hover {
            background-color: #fff;
            border-color: #7c4dff;
            transform: scale(1.05);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }
    </style>

    <div class="py-20 px-4 md:px-10 max-w-7xl mx-auto">
        {{-- Header Section --}}
        <div class="text-center mb-16">
            <h1 class="font-normal text-3xl text-slate-700 uppercase tracking-[0.2em] leading-none mb-4">
                {{ __('Our Services') }}
            </h1>
            <p class="text-slate-400 font-medium uppercase tracking-[0.18em] text-[10px]">
                Professional laundry services tailored to your needs
            </p>
            <div class="mt-6 flex justify-center">
                <div class="w-12 h-[1px] bg-[#7c4dff]/30"></div>
            </div>
        </div>

        {{-- Services Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 mb-20">
            @php
                $services = [
                    ['title' => 'Basic wash & fold', 'desc' => 'Standard washing and folding service for everyday clothes.', 'price' => 'Php 150', 'unit' => '2.5 kg', 'time' => '24hrs to 48hrs', 'is_addon' => false],
                    ['title' => 'Dry Cleaning', 'desc' => 'Professional dry cleaning for delicate garments.', 'price' => 'Php 350', 'unit' => 'Per Item', 'time' => '48-72 hours', 'is_addon' => false],
                    ['title' => 'Express service', 'desc' => 'Ultra-fast washing and folding for urgent needs.', 'price' => 'Php 250', 'unit' => 'Add on', 'time' => '12hrs to 24hrs', 'is_addon' => true],
                    ['title' => 'Delicate care', 'desc' => 'Special care for delicate fabrics and garments.', 'price' => 'Php 300', 'unit' => 'Add on', 'time' => '48-72 hours', 'is_addon' => true],
                    ['title' => 'Ironing service', 'desc' => 'Professional ironing and pressing service.', 'price' => 'Php 120', 'unit' => '2.5 kg', 'time' => '24hrs', 'is_addon' => false],
                    ['title' => 'Stain Treatment', 'desc' => 'Specialized treatment for tough and stubborn stains.', 'price' => 'Php 200', 'unit' => 'Per Item', 'time' => '24hrs to 48hrs', 'is_addon' => false],
                ];
            @endphp

            @foreach($services as $service)
                <div class="service-card bg-white p-10 rounded-[3rem] flex flex-col h-full shadow-sm">
                    <h5 class="text-xs font-bold text-slate-800 uppercase tracking-[0.15em] mb-3">{{ $service['title'] }}</h5>
                    <p class="text-[13px] text-slate-500 leading-relaxed mb-8 flex-grow">{{ $service['desc'] }}</p>
                    
                    <div class="mb-6">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] block mb-2">Investment</span>
                        <span class="text-lg font-medium text-[#7c4dff] tracking-tight">
                            {{ $service['price'] }} <span class="text-slate-300 font-light text-sm italic">/ {{ $service['unit'] }}</span>
                        </span>
                    </div>
                    
                    <div class="flex items-center mb-8 text-slate-400 text-[10px] font-bold uppercase tracking-widest">
                        <svg class="w-3.5 h-3.5 mr-2 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Est: {{ $service['time'] }}
                    </div>
                    
                    @if($service['is_addon'])
                        <button class="w-full py-4 bg-slate-50 text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em] rounded-full border border-slate-100 cursor-not-allowed">
                            Complementary Add-on
                        </button>
                    @else
                        <x-primary-button onclick="window.location='{{ auth()->check() ? route('orders.create') : route('login') }}'" 
                            class="!w-full !justify-center !py-4 !rounded-full !text-[10px] !font-black !uppercase !tracking-widest !bg-[#7c4dff] shadow-lg shadow-indigo-100 transition-all hover:scale-[1.02]">
                            Book This Service
                        </x-primary-button>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Pricing Information Section --}}
        <div class="bg-white rounded-[4rem] p-12 md:p-20 border border-slate-100 shadow-sm text-center">
            <div class="max-w-3xl mx-auto">
                <h2 class="font-normal text-2xl text-slate-700 uppercase tracking-[0.2em] mb-4">
                    {{ __('Weight-Based Scales') }}
                </h2>
                <p class="text-[13px] text-slate-500 leading-relaxed mb-12">
                    For <span class="text-slate-800 font-semibold">Wash & Fold</span> and <span class="text-slate-800 font-semibold">Ironing</span>, 
                    pricing is calculated based on load density. Base rates cover up to 2.5 kg.
                </p>
                
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-5">
                    @php
                        $weights = [
                            ['val' => '2.5 kg', 'label' => 'Base Rate'],
                            ['val' => '3.0 kg', 'label' => '+20%'],
                            ['val' => '3.5 kg', 'label' => '+40%'],
                            ['val' => '4.0 kg', 'label' => '+60%'],
                            ['val' => '4.5 kg', 'label' => '+80%'],
                            ['val' => '5.0 kg', 'label' => '+100%'],
                        ];
                    @endphp

                    @foreach($weights as $weight)
                        <div class="weight-box bg-slate-50 p-6 rounded-[2rem] border border-slate-100 flex flex-col justify-center">
                            <div class="text-[15px] font-medium text-slate-700 mb-1">{{ $weight['val'] }}</div>
                            <div class="text-[8px] font-bold text-[#7c4dff] uppercase tracking-[0.2em]">{{ $weight['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>