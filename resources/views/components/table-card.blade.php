@props(['headers' => []])

<div class="bg-white rounded-[2rem] border border-slate-200 shadow-2xl shadow-slate-200/40 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-center border-collapse table-fixed">
            {{-- Slot for the x-table-header component --}}
            {{ $header ?? '' }}
            
            <tbody class="divide-y divide-slate-100 bg-white">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>