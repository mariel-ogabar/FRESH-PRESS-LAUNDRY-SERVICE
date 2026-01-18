@props(['active'])

@php
$classes = ($active ?? false)
            ? 'px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest bg-black text-white shadow-sm transition-all duration-200'
            : 'px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest bg-gray-50 text-gray-400 hover:text-gray-900 hover:bg-gray-100 transition-all duration-200 active:scale-95';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>