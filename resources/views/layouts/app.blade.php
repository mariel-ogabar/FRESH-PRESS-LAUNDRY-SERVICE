<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'FreshPress') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-fp-dark">
    <div class="min-h-screen flex flex-col">
        {{-- I-include ang updated navigation --}}
        @include('layouts.navigation')

        {{-- Page Heading (Header Slot) --}}
        @if (isset($header))
            <header class="bg-white border-b border-gray-100">
                <div class="max-w-7xl mx-auto py-8 px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        {{-- Main Page Content --}}
        <main class="flex-grow py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>

        <footer class="py-10 text-center border-t border-gray-100 bg-white">
            <p class="text-[10px] font-bold text-gray-300 uppercase tracking-[0.4em]">© 2026 FreshPress Laundry Operations</p>
        </footer>
    </div>
</body>
</html>