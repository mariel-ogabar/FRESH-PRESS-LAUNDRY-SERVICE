@props(['currentStatus', 'options' => [], 'disabled' => false, 'terminal' => false])

@php
    $status = strtoupper($currentStatus);
    
    $isSuccess = in_array($status, ['RECEIVED', 'PAID', 'COMPLETED', 'DELIVERED', 'READY', 'COLLECTED', 'ONLINE', 'ACTIVE']);
    $isWarning = in_array($status, ['PENDING', 'AWAITING_PAYMENT']);
    $isDanger  = in_array($status, ['CANCELLED', 'VOID', 'EXPIRED', 'OFFLINE', 'INACTIVE']);

    $theme = match(true) {
        $isSuccess => (object)['bg'=>'bg-emerald-50', 'text'=>'text-emerald-600', 'border'=>'border-emerald-100'],
        $isDanger  => (object)['bg'=>'bg-rose-50', 'text'=>'text-rose-500', 'border'=>'border-rose-100'],
        $isWarning => (object)['bg'=>'bg-amber-50', 'text'=>'text-amber-600', 'border'=>'border-amber-100'],
        default    => (object)['bg'=>'bg-slate-50', 'text'=>'text-slate-500', 'border'=>'border-slate-200']
    };

    $sharedClasses = "text-[10px] font-medium uppercase tracking-[0.15em] w-[140px] h-[38px]";
@endphp

<div class="flex items-center justify-center">
    @if($terminal || ($disabled && !$attributes->has('::disabled')))
        {{-- Terminal State Static Badge --}}
        <span class="{{ $sharedClasses }} {{ $theme->bg }} {{ $theme->text }} border {{ $theme->border }} 
                    inline-flex items-center justify-center px-4 rounded-xl shadow-sm transition-all duration-300">
            @if($isSuccess)
                <span class="mr-1.5 w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
            @endif
            {{ $status }}
        </span>
    @else
        {{-- Interactive State Clean Dropdown --}}
        <div class="relative group">
            <select 
                {{ $disabled ? 'disabled' : '' }}
                {!! $attributes->merge([
                    'class' => "block $sharedClasses rounded-xl border appearance-none py-0 px-3 transition-all duration-200 
                                focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-300 shadow-sm
                                $theme->bg $theme->border $theme->text cursor-pointer hover:brightness-95
                                disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-slate-100"
                ]) !!}
            >
                @foreach($options as $value => $label)
                    <option value="{{ $value }}" {{ strtoupper($currentStatus) == strtoupper($value) ? 'selected' : '' }} 
                            class="bg-white text-slate-700 font-medium">
                        {{ strtoupper($label) }}
                    </option>
                @endforeach
            </select>
            
            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none {{ $theme->text }} opacity-50">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>
        </div>
    @endif
</div>