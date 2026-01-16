@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-fp-accent text-[11px] font-black uppercase tracking-widest text-fp-dark transition'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-[11px] font-bold uppercase tracking-widest text-gray-400 hover:text-fp-dark transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>