{{-- Usage: <x-data-text>#{{ $order->id }}</x-data-text> --}}
<span {{ $attributes->merge(['class' => 'text-[11px] font-black text-slate-500 uppercase tracking-[0.15em]']) }}>
    {{ $slot }}
</span>