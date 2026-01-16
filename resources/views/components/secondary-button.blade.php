<button {{ $attributes->merge([
    'type' => 'button', 
    'class' => 'inline-flex items-center px-8 py-3 bg-white border-2 border-gray-100 rounded-full font-bold text-xs text-gray-600 uppercase tracking-widest hover:border-fp-dark hover:text-fp-dark hover:shadow-md active:bg-gray-50 active:scale-95 focus:outline-none focus:ring-2 focus:ring-fp-accent focus:ring-offset-2 transition all duration-150 ease-in-out cursor-pointer disabled:opacity-25'
]) }}>
    {{ $slot }}
</button>