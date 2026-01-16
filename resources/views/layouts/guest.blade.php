<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FreshPress') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-fp-dark antialiased bg-fp-soft">
    <div class="min-h-screen flex flex-col">
        
        {{-- 
            Dito natin tinatawag ang navigation.blade.php.
            Dahil ginagamit ito ng Layout, hindi mo na kailangan 
            tawagin ang <x-fp-nav /> sa loob ng login.blade.php 
        --}}
        @include('layouts.navigation')

        {{-- Page Content --}}
        <main class="flex-grow flex flex-col items-center justify-center py-12 px-6">
            {{ $slot }}
        </main>

        {{-- Simple Footer --}}
        <footer class="py-12 bg-white text-center border-t border-gray-100">
            <p class="text-[10px] font-bold uppercase tracking-[0.4em] text-gray-300">
                © 2026 FreshPress Laundry Service • Quality Reimagined
            </p>
        </footer>
    </div>
</body>
</html>