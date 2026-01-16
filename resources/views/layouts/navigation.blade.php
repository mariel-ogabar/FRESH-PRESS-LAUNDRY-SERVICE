<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10">
        <div class="flex justify-between h-16"> 
            
            <div class="flex items-center">
                <a href="/" class="shrink-0 flex items-center space-x-4 group">
                    <div class="flex-shrink-0 w-10 h-10 bg-black rounded-xl flex items-center justify-center shadow-sm transition-transform group-hover:scale-105">
                        <svg class="h-6 w-6 text-indigo-500" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2l2.4 7.4h7.6l-6.2 4.5 2.4 7.4-6.2-4.5-6.2 4.5 2.4-7.4-6.2-4.5h7.6z" />
                        </svg>
                    </div>
                    <div>
                        <span class="block text-lg font-black text-gray-900 tracking-tighter uppercase leading-none">FreshPress</span>
                        <span class="flex items-center gap-1.5 text-[10px] text-gray-500 uppercase tracking-wider font-bold mt-1">
                            @auth 
                                <span class="text-gray-900">{{ auth()->user()->getRoleNames()->first() }} PANEL</span> 
                                <span class="text-indigo-400 font-light">|</span> 
                                <span class="text-indigo-600 italic lowercase tracking-normal text-xs font-semibold">{{ auth()->user()->name }}</span>
                            @else 
                                PREMIUM LAUNDRY SERVICE 
                            @endauth
                        </span>
                    </div>
                </a>
            </div>

            <div class="flex items-center space-x-2">
                <div class="hidden md:flex md:items-center md:space-x-1.5">
                    
                    @guest
                        <x-nav-link :href="url('/')" :active="request()->is('/')">Home</x-nav-link>
                        <x-nav-link :href="url('/#services')" :active="request()->is('#services')">Services</x-nav-link>
                        <x-nav-link :href="route('login')" :active="request()->routeIs('login')">Log In</x-nav-link>
                        <x-nav-link :href="route('register')" :active="request()->routeIs('register')">Sign Up</x-nav-link>
                    @else
                        {{-- ADMIN & STAFF NAVIGATION --}}
                        @hasanyrole('ADMIN|STAFF')
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>
                            
                            @can('create orders')
                                <x-nav-link :href="route('orders.create')" :active="request()->routeIs('orders.create')">Walk-in</x-nav-link>
                            @endcan

                            @can('manage staff')
                                <x-nav-link :href="route('admin.staff.index')" :active="request()->routeIs('admin.staff.*')">Staff</x-nav-link>
                            @endcan

                            @can('manage services')
                                <x-nav-link :href="route('admin.services.index')" :active="request()->routeIs('admin.services.*')">Service Management</x-nav-link>
                            @endcan
                        @endhasanyrole

                        {{-- CUSTOMER NAVIGATION --}}
                        @hasrole('CUSTOMER')
                            <x-nav-link :href="url('/')" :active="request()->is('/')">Home</x-nav-link>
                            <x-nav-link :href="url('/#services')" :active="request()->is('#services')">Services</x-nav-link>
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>
                            <x-nav-link :href="route('orders.create')" :active="request()->routeIs('orders.create')">Book Now</x-nav-link>
                        @endhasrole
                    @endguest
                </div>

                <div class="flex items-center">
                    @auth
                        <button @click="open = ! open" class="p-2.5 bg-gray-50 rounded-xl text-gray-400 hover:bg-gray-100 transition-all active:scale-90">
                            <svg class="h-5 w-5 transition-transform duration-300" :class="{'rotate-90': open}" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @else
                        <div class="md:hidden">
                            <button @click="open = ! open" class="p-2.5 bg-gray-50 rounded-xl text-gray-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                            </button>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         @click.away="open = false"
         class="absolute right-4 top-16 w-64 bg-white border border-gray-100 rounded-2xl shadow-2xl z-50 overflow-hidden">
        
        <div class="p-4">
            @guest
                <div class="md:hidden space-y-1">
                    <a href="{{ url('/') }}" class="block px-4 py-2 text-xs font-bold uppercase tracking-widest text-gray-600">Home</a>
                    <a href="{{ route('login') }}" class="block px-4 py-2 text-xs font-bold uppercase text-gray-600">Log In</a>
                    <a href="{{ route('register') }}" class="block px-4 py-2 text-xs font-bold uppercase text-white bg-black rounded-lg text-center">Sign Up</a>
                </div>
            @else
                <div class="flex items-center px-4 py-3 bg-gray-50 rounded-xl mb-4">
                    <div class="w-9 h-9 bg-black rounded-lg flex items-center justify-center text-white text-xs font-black">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="ml-3 overflow-hidden">
                        <p class="text-xs font-black text-gray-900 uppercase leading-none truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-indigo-500 font-bold uppercase mt-1">{{ auth()->user()->getRoleNames()->first() }}</p>
                    </div>
                </div>

                <div class="space-y-1">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-xs font-bold uppercase tracking-widest text-gray-600 hover:bg-gray-50 rounded-lg transition {{ request()->routeIs('profile.edit') ? 'text-black font-black' : '' }}">
                        Profile Settings
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-xs font-bold uppercase tracking-widest text-red-500 hover:bg-red-50 rounded-lg transition">
                            Log Out
                        </button>
                    </form>
                </div>
            @endguest
        </div>
    </div>
</nav>