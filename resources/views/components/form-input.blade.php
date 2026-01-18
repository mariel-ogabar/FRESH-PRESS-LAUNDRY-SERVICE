@props(['disabled' => false, 'label' => null])

<div class="w-full">
    @if($label)
        <label class="block text-[11px] font-black text-slate-700 uppercase tracking-widest mb-2 ml-1">
            {{ $label }}
        </label>
    @endif
    <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-full border-2 border-slate-100 rounded-xl p-3 text-sm font-bold text-slate-700 focus:border-indigo-500 focus:ring-0 transition-all uppercase placeholder:text-slate-300']) !!}>
</div>