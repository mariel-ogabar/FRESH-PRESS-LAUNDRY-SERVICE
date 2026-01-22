@props(['name', 'label'])

<select name="{{ $name }}" {{ $attributes->merge(['class' => 'rounded-lg border-gray-300 text-xs font-bold uppercase tracking-wider focus:ring-indigo-500 focus:border-indigo-500']) }}>
    <option value="">{{ $label }}</option>
    {{ $slot }}
</select>