@php
    $classes = match($status) {
        'ACTIVE' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
        'COMPLETED' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
        'CANCELLED' => 'bg-slate-100 text-slate-500 border-slate-200',
        default => 'bg-gray-100 text-gray-600 border-gray-200',
    };
@endphp
<span class="{{ $classes }} px-2 py-0.5 rounded-full border text-[10px] font-black tracking-tight">
    {{ $status }}
</span>