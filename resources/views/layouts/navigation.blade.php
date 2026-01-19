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