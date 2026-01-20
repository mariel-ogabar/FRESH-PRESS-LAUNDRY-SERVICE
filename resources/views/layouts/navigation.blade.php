<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 sticky top-0 z-50">
    {{-- 1. ANTI-FLASH STYLE --}}
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <div class="max-w-[90rem] mx-auto px-4 md:px-10">
        <div class="flex justify-between h-20"> 
            
            {{-- Brand Section --}}
            <div class="flex items-center">
                <a href="/" class="shrink-0 flex items-center group">
                    <div>
                                                <svg width="50" height="42" viewBox="0 0 50 42" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <rect width="50" height="42" fill="url(#pattern0_20_9)"/>
                            <defs>
                                <pattern id="pattern0_20_9" patternContentUnits="objectBoundingBox" width="1" height="1">
                                    <use xlink:href="#image0_20_9" transform="matrix(0.0025 0 0 0.00297619 -0.00875 0)"/>
                                </pattern>
                                <image id="image0_20_9" width="407" height="336" preserveAspectRatio="none" xlink:href="data:image/png;base64,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"/>
                            </defs>
                        </svg>
                    </div>
                    <div>
                        <span class="block text-xl font-black text-gray-900 tracking-tighter uppercase leading-none">FreshPress</span>
                        <span class="flex items-center gap-1.5 text-[10px] text-gray-500 uppercase tracking-widest font-bold mt-1.5">
                            @auth 
                                <span class="text-gray-900">{{ auth()->user()->getRoleNames()->first() }} PANEL</span> 
                                <span class="text-indigo-400 font-light">|</span> 
                                <span class="text-indigo-600 lowercase tracking-normal text-[11px] font-semibold">{{ auth()->user()->name }}</span>
                            @else 
                                PREMIUM LAUNDRY SERVICE 
                            @endguest
                        </span>
                    </div>
                </a>
            </div>

            {{-- Desktop Navigation & User Trigger --}}
            <div class="flex items-center gap-6">
                
                <div class="hidden lg:flex lg:items-center lg:gap-2">
                    @guest
                        <x-nav-link :href="url('/')" :active="request()->is('/')">Home</x-nav-link>
                        <x-nav-link :href="url('/#services')" :active="request()->is('#services')">Services</x-nav-link>
                        <x-nav-link :href="route('login')" :active="request()->routeIs('login')">Log In</x-nav-link>
                        <x-nav-link :href="route('register')" :active="request()->routeIs('register')">Sign Up</x-nav-link>
                    @else
                        {{-- Shared Dashboard --}}
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>

                        {{-- Use specific functional permission instead of Role --}}
                        @can('create orders')
                            <x-nav-link :href="route('orders.create')" :active="request()->routeIs('orders.create')">
                                {{-- Label logic can still be contextual based on role if preferred --}}
                                {{ auth()->user()->hasRole('CUSTOMER') ? __('Book Service') : __('Walk-in Order') }}
                            </x-nav-link>
                        @endcan

                        {{-- Admin Specific Permissions --}}
                        @can('manage staff')
                            <x-nav-link :href="route('admin.staff.index')" :active="request()->routeIs('admin.staff.*')">Staff</x-nav-link>
                        @endcan

                        @can('manage services')
                            <x-nav-link :href="route('admin.services.index')" :active="request()->routeIs('admin.services.*')">Services</x-nav-link>
                        @endcan
                    @endguest
                </div>

                {{-- Action Menu Trigger --}}
                <button @click="open = ! open" 
                        class="flex items-center gap-3 p-2 pl-4 bg-gray-50 border border-gray-100 rounded-2xl hover:bg-gray-100 transition-all active:scale-95 group">
                    <span class="hidden md:block text-[10px] font-bold uppercase tracking-widest text-gray-500 group-hover:text-gray-900 transition-colors">Menu</span>
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm border border-gray-200">
                        <svg class="h-5 w-5 text-gray-600 transition-transform duration-300" :class="{'rotate-90': open}" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </button>
            </div>
        </div>
    </div>

    {{-- Dropdown Menu --}}
    <div x-show="open" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         @click.away="open = false"
         class="absolute right-4 md:right-10 top-[88px] w-72 bg-white border border-gray-100 rounded-[2rem] shadow-2xl z-50 overflow-hidden">
        
        <div class="p-6">
            @auth
                {{-- Authenticated User Info --}}
                <div class="flex items-center px-4 py-4 bg-gray-50 rounded-2xl mb-6">
                    <div class="w-10 h-10 bg-gray-900 rounded-xl flex items-center justify-center text-white text-xs font-black shadow-lg shadow-gray-200 uppercase">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="ml-3 overflow-hidden">
                        <p class="text-xs font-black text-gray-900 uppercase leading-none truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-indigo-500 font-bold uppercase tracking-widest mt-1.5">{{ auth()->user()->getRoleNames()->first() }}</p>
                    </div>
                </div>

                {{-- Navigation Links (Mobile Dropdown) --}}
                <div class="lg:hidden space-y-1 mb-6 border-b border-gray-50 pb-6">
                    <p class="px-4 text-[9px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3">Navigation</p>
                    
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-gray-600 hover:bg-gray-50 rounded-xl">Dashboard</a>
                    
                    @can('create orders')
                        <a href="{{ route('orders.create') }}" class="block px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-indigo-600 hover:bg-indigo-50 rounded-xl">
                            {{ auth()->user()->hasRole('CUSTOMER') ? __('Book Service') : __('Walk-in Order') }}
                        </a>
                    @endcan

                    @can('manage staff')
                        <a href="{{ route('admin.staff.index') }}" class="block px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-gray-600 hover:bg-gray-50 rounded-xl">Staff</a>
                    @endcan

                    @can('manage services')
                        <a href="{{ route('admin.services.index') }}" class="block px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-gray-600 hover:bg-gray-50 rounded-xl">Services</a>
                    @endcan
                </div>

                {{-- User Settings --}}
                <div class="space-y-1">
                    <p class="px-4 text-[9px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3">System Access</p>
                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 text-xs font-bold uppercase tracking-widest text-gray-600 hover:bg-gray-50 rounded-xl transition">
                        Profile Settings
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-center px-4 py-3 text-xs font-bold uppercase tracking-widest text-rose-500 hover:bg-rose-50 rounded-xl transition font-black">
                            Log Out
                        </button>
                    </form>
                </div>
            @else
                <div class="space-y-3">
                    <a href="{{ route('login') }}" class="block px-4 py-4 text-xs font-bold uppercase tracking-widest text-gray-600 hover:bg-gray-50 rounded-2xl text-center border border-gray-100">Log In</a>
                    <a href="{{ route('register') }}" class="block px-4 py-4 text-xs font-bold uppercase tracking-widest text-white bg-gray-900 rounded-2xl text-center shadow-lg shadow-gray-200">Sign Up Now</a>
                </div>
            @endauth
        </div>
    </div>
</nav>