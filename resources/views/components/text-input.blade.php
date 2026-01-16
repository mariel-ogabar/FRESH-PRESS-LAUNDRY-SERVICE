@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-200 bg-white/50 focus:border-fp-accent focus:ring-fp-accent rounded-xl shadow-sm py-3 transition-all']) !!}>