<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'FreshPress') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-fp-dark antialiased bg-fp-soft">
    {{-- Main Wrapper --}}
    <div class="min-h-screen flex flex-col">
        
        {{-- Navigation - Fixed height and proper flow --}}
        <nav class="w-full bg-transparent">
            <div class="max-w-7xl mx-auto px-6 lg:px-10 py-6 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-8 h-8 text-fp-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.921-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.784.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    <span class="text-xl font-black tracking-tighter uppercase">FreshPress</span>
                </div>

                <div class="flex items-center gap-4 lg:gap-8">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}">
                                <x-secondary-button class="px-6 py-2 text-xs border-none">Dashboard</x-secondary-button>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-bold text-fp-dark hover:text-fp-accent transition">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}">
                                    <x-primary-button class="px-6 py-2.5 text-xs rounded-full">Register</x-primary-button>
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </nav>

        {{-- Page Content --}}
        <div class="flex-grow flex flex-col">
            {{ $slot }}
        </div>

        {{-- Simple Footer --}}
        <footer class="py-10 text-center text-gray-400 text-[10px] font-bold uppercase tracking-[0.2em]">
            &copy; 2026 FreshPress Laundry Service.
        </footer>
    </div>
</body>
</html>