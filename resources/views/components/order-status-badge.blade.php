@props(['status'])

@php
    $classes = match($status) {
        \App\Models\Order::STATUS_ACTIVE => 'bg-green-100 text-green-700',
        \App\Models\Order::STATUS_CANCELLED => 'bg-red-100 text-red-700',
        \App\Models\Order::STATUS_COMPLETED => 'bg-gray-100 text-gray-700',
        \App\Models\Payment::STATUS_PAID => 'text-green-600 font-bold',
        \App\Models\Payment::STATUS_PENDING => 'text-orange-500 font-bold',
        default => 'bg-blue-100 text-blue-700',
    };
@endphp

<span {{ $attributes->merge(['class' => "px-2 py-0.5 rounded-full text-[10px] uppercase font-bold $classes"]) }}>
    {{ str_replace('_', ' ', $status) }}
</span>