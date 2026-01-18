@props(['label', 'value'])

<div class="bg-white overflow-hidden shadow-sm rounded-[1.5rem] border border-slate-200 transition-all hover:shadow-md active:scale-[0.98]">
    <div class="p-5 md:p-8">
        <p class="text-[9px] md:text-[10px] font-medium text-slate-500 uppercase tracking-[0.2em] leading-none mb-3 md:mb-5">
            {{ $label }}
        </p>
        
        <p class="text-lg md:text-2xl font-medium text-slate-900 tracking-tighter truncate">
            {{ $value }}
        </p>
    </div>
</div>