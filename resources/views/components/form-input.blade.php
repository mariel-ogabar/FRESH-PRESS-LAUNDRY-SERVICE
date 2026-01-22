@props(['disabled' => false, 'label' => null])

<div class="w-full">
    @if($label)
        <label class="text-[10px] font-bold text-black uppercase tracking-[0.25em]">
            {{ $label }}
        </label>
    @endif
    <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-full border-2 border-slate-100 rounded-xl p-3 text-sm font-bold text-slate-700 focus:border-indigo-500 focus:ring-0 transition-all uppercase placeholder:text-slate-300']) !!}>
</div>