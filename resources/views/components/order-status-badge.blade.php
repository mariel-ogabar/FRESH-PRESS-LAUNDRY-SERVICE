@props(['status'])

@php
    // Normalize status to uppercase for reliable matching
    $status = strtoupper($status);

    $classes = match($status) {
        // Active / In-Progress States (Indigo)
        'ACTIVE', 'WASHING', 'DRYING', 'FOLDING', 'IRONING' 
            => 'bg-indigo-50 text-indigo-700 border-indigo-200',

        // Success / Terminal States (Emerald)
        'COMPLETED', 'READY', 'PAID', 'RECEIVED', 'DELIVERED' 
            => 'bg-emerald-50 text-emerald-700 border-emerald-200',

        // Warning / Pending States (Amber)
        'PENDING' 
            => 'bg-amber-50 text-amber-700 border-amber-200',

        // Neutral / Archived States (Slate)
        'CANCELLED' 
            => 'bg-slate-100 text-slate-500 border-slate-200',

        default => 'bg-gray-100 text-gray-600 border-gray-200',
    };
@endphp

<span class="{{ $classes }} px-3 py-1 rounded-full border text-[10px] font-black tracking-widest uppercase inline-flex items-center justify-center min-w-[100px]">
    {{-- Status Dot for Ready/Completed items --}}
    @if(in_array($status, ['COMPLETED', 'READY', 'PAID', 'RECEIVED', 'DELIVERED']))
        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2 shadow-sm"></span>
    @endif
    
    {{ str_replace('_', ' ', $status) }}
</span>