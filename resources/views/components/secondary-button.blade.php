@props(['href' => null])

@php
    $classes = 'inline-flex items-center px-4 py-2 bg-slate-600 border border-transparent rounded-xl font-black text-[10px] text-white uppercase tracking-widest hover:bg-slate-700 active:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-slate-200';
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => 'button', 'class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif