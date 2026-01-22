@props(['href' => null])

@php
    $classes = 'relative inline-flex items-center justify-center px-8 py-4 bg-[#f5f0ff] border-2 border-[#7c4dff]/20 rounded-[1.5rem] font-black text-[11px] text-[#7c4dff] uppercase tracking-[0.15em] transition-all duration-300 hover:bg-[#7c4dff] hover:text-white hover:shadow-xl hover:shadow-indigo-200 active:scale-95 focus:outline-none focus:ring-2 focus:ring-[#7c4dff] focus:ring-offset-2 disabled:opacity-50 cursor-pointer overflow-hidden group shadow-sm';
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        <span class="relative z-10">{{ $slot }}</span>
    </a>
@else
    <button {{ $attributes->merge(['type' => 'button', 'class' => $classes]) }}>
        <span class="relative z-10">{{ $slot }}</span>
    </button>
@endif