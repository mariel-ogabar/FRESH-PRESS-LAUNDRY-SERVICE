@props(['href' => null])

@php
    $classes = 'relative inline-flex items-center justify-center px-8 py-4 bg-[#7c4dff] border border-transparent rounded-[1.5rem] font-black text-[11px] text-white uppercase tracking-[0.15em] shadow-lg shadow-indigo-200 transition-all duration-200 hover:opacity-90 hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 active:scale-95 focus:outline-none focus:ring-2 focus:ring-[#7c4dff] focus:ring-offset-2 disabled:opacity-50 cursor-pointer overflow-hidden group';
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        <span class="relative z-10">{{ $slot }}</span>
    </a>
@else
    <button {{ $attributes->merge(['type' => 'submit', 'class' => $classes]) }}>
        <span class="relative z-10">{{ $slot }}</span>
    </button>
@endif