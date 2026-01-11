<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            
            <div class="flex items-center">
                <div class="shrink-0 flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M12 3l1.912 5.885h6.19l-5.007 3.638 1.912 5.885L12 14.77l-5.007 3.638 1.912-5.885-5.007-3.638h6.19L12 3z" />
                        </svg>
                    </div>
                    <div>
                        <span class="block text-lg font-bold text-gray-900 leading-tight">FreshPress Laundry</span>
                        <span class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">
                            @hasrole('ADMIN') admin panel @elsehasrole('STAFF') staff panel @else customer portal @endhasrole
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex items-center space-x-8">
                <div class="hidden sm:flex sm:items-center sm:space-x-8">
                    
                    {{-- CUSTOMER NAVIGATION --}}
                    @hasrole('CUSTOMER')
                        <a href="/" class="inline-flex items-center text-sm font-bold transition duration-150 ease-in-out px-1 pt-1 border-b-2 {{ request()->is('/') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Home
                        </a>

                        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-bold transition duration-150 ease-in-out px-1 pt-1 border-b-2 {{ request()->routeIs('services.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            Service
                        </a>

                        <a href="{{ route('orders.create') }}" class="inline-flex items-center text-sm font-bold transition duration-150 ease-in-out px-1 pt-1 border-b-2 {{ request()->routeIs('orders.create') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 001 1z"/></svg>
                            Book Service
                        </a>

                        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-bold transition duration-150 ease-in-out px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            My Orders
                        </a>
                    @endhasrole

                    {{-- ADMIN & STAFF NAVIGATION --}}
                    @hasanyrole('ADMIN|STAFF')
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-bold transition duration-150 ease-in-out px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                            Dashboard
                        </a>

                        {{-- SEPARATED: Walk-in Orders (uses create orders permission) --}}
                        @can('create orders')
                            <a href="{{ route('orders.create') }}" class="inline-flex items-center text-sm font-bold transition duration-150 ease-in-out px-1 pt-1 border-b-2 {{ request()->routeIs('orders.create') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                Walk-in Order
                            </a>
                        @endcan

                        {{-- SEPARATED: Staff Management (uses manage staff permission) --}}
                        @can('manage staff')
                            <a href="{{ route('admin.staff.index') }}" class="inline-flex items-center text-sm font-bold transition duration-150 ease-in-out px-1 pt-1 border-b-2 {{ request()->routeIs('admin.staff.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Staff Management
                            </a>
                        @endcan

                        {{-- SEPARATED: Services & Pricing (uses manage services permission) --}}
                        @can('manage services')
                            <a href="{{ route('admin.services.index') }}" class="inline-flex items-center text-sm font-bold transition duration-150 ease-in-out px-1 pt-1 border-b-2 {{ request()->routeIs('admin.services.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Services
                            </a>
                        @endcan
                    @endhasanyrole
                </div>

                <div class="sm:flex sm:items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center p-2 border border-gray-200 rounded-md text-gray-500 bg-white hover:bg-gray-50 focus:outline-none transition">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="block px-4 py-2 text-xs text-gray-400 font-bold uppercase tracking-widest">
                                Role: {{ auth()->user()->getRoleNames()->first() ?? 'None' }}
                            </div>
                            <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                            <div class="border-t border-gray-100"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-600 font-bold">
                                    Log Out
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>
    </div>
</nav>