@props(['headers'])

<thead class="bg-slate-100 border-b-2 border-slate-300">
    <tr class="text-[10px] font-medium text-slate-800 uppercase tracking-[0.25em]">
        @foreach($headers as $header)
            <th class="px-4 py-7 font-medium text-center" style="width: {{ $header['width'] ?? 'auto' }}">
                {{ $header['name'] }}
            </th>
        @endforeach
    </tr>
</thead>