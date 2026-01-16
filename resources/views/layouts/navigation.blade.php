<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10">
        <div class="flex justify-between h-20">
            
            <div class="flex items-center">
                <a href="/" class="shrink-0 flex items-center space-x-4 group">
                    <div class="flex-shrink-0 w-10 h-10 bg-black rounded-xl flex items-center justify-center group-hover:rotate-12 transition-all shadow-sm">
                        <svg class="h-6 w-6 text-fp-accent" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2l2.4 7.4h7.6l-6.2 4.5 2.4 7.4-6.2-4.5-6.2 4.5 2.4-7.4-6.2-4.5h7.6z" />
                        </svg>
                    </div>
                    <div class="hidden sm:block">
                        <span class="block text-xl font-black text-gray-900 leading-tight tracking-tighter uppercase">FreshPress</span>
                        <span class="flex items-center gap-1.5 text-[10px] text-gray-400 uppercase tracking-widest font-bold">
                            @auth
                                <span class="text-fp-accent">●</span>
                                @hasrole('ADMIN') Admin Panel @elsehasrole('STAFF') Staff Panel @else Customer Portal @endhasrole
                                <span class="text-gray-300 mx-1">|</span>
                                <span class="text-gray-500 italic lowercase tracking-normal">{{ auth()->user()->name }}</span>
                            @else
                                Laundry Service
                            @endauth
                        </span>
                    </div>
                </a>
            </div>

            <div class="flex items-center space-x-6">
                <div class="hidden md:flex md:items-center md:space-x-6">
                    @auth
                        @hasrole('CUSTOMER')
                            <x-nav-link :href="url('/')" :active="request()->is('/')">Home</x-nav-link>
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('services.*')">Services</x-nav-link>
                            <x-nav-link :href="route('orders.create')" :active="request()->routeIs('orders.create')">Book Service</x-nav-link>
                        @endhasrole

                        @hasanyrole('ADMIN|STAFF')
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>
                            @can('create orders')
                                <x-nav-link :href="route('orders.create')" :active="request()->routeIs('orders.create')">Walk-in</x-nav-link>
                            @endcan
                            @can('manage staff')
                                <x-nav-link :href="route('admin.staff.index')" :active="request()->routeIs('admin.staff.*')">Staff</x-nav-link>
                            @endcan
                        @endhasanyrole
                    @endauth
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center p-2.5 border border-gray-100 rounded-xl text-gray-500 bg-gray-50/50 hover:bg-white hover:shadow-sm focus:outline-none transition-all">
                                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="px-4 py-3 border-b border-gray-50">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Signed in as</p>
                                    <p class="text-sm font-bold text-gray-900 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <x-dropdown-link :href="route('profile.edit')">Account Settings</x-dropdown-link>
                                <div class="border-t border-gray-50"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-500 font-bold">
                                        Log Out
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    @else
                        <div class="flex items-center gap-4">
                            <a href="{{ route('login') }}" class="text-[11px] font-bold uppercase tracking-widest text-gray-500 hover:text-black transition">Login</a>
                            <x-primary-button onclick="window.location='{{ route('register') }}'" class="py-2.5 px-6">Register</x-primary-button>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden bg-white border-t border-gray-100 animate-fade-in-down">
        <div class="pt-2 pb-3 space-y-1 px-4">
            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-responsive-nav-link>
                @hasrole('CUSTOMER')
                    <x-responsive-nav-link :href="route('orders.create')" :active="request()->routeIs('orders.create')">Book Service</x-responsive-nav-link>
                @endhasrole
            @endauth
        </div>
    </div>
</nav>