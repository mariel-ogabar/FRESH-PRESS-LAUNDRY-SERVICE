<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-8 py-3 bg-white border-2 border-gray-100 rounded-full font-bold text-xs text-gray-600 uppercase tracking-widest hover:border-fp-dark transition duration-150']) }}>
    {{ $slot }}
</button>