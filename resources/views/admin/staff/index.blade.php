<x-app-layout>
    
    <div class="py-8 px-4 md:px-10 max-w-[90rem] mx-auto space-y-10">

<div class="flex flex-col items-center justify-center text-center gap-6 px-2 md:px-4 mb-16">
    <div>
        <h2 class="font-normal text-3xl text-slate-700 uppercase tracking-widest leading-none">
            {{ __('Staff Management') }}
        </h2>
        <p class="text-[11px] font-medium text-slate-400 uppercase tracking-[0.18em] mt-3">
            {{ __('Manage team access and permissions.') }}
        </p>
    </div>                

    <div class="flex shrink-0">
        <x-primary-button 
            onclick="window.location='{{ route('admin.staff.create') }}'" 
            class="!py-4 !px-10 !rounded-full !text-[11px] !font-black !uppercase !tracking-widest shadow-xl shadow-indigo-100 !bg-[#7c4dff] !text-white border-none transition-all hover:scale-105 active:scale-95"
        >
            {{ __('+ ADD STAFF') }}
        </x-primary-button>
    </div>
</div>
        {{-- Validation & Action Feedback --}}
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-xl flex items-center gap-3 animate-fade-in shadow-sm">
                <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="text-[10px] font-medium text-emerald-700 uppercase tracking-widest">SUCCESS: {{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-100 p-4 rounded-xl flex items-center gap-3 shadow-sm">
                <svg class="w-4 h-4 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <span class="text-[10px] font-medium text-rose-700 uppercase tracking-widest">ALERT: {{ session('error') }}</span>
            </div>
        @endif

        {{-- Staff Table Container --}}
        <div class="overflow-x-auto rounded-[2rem] shadow-2xl border border-slate-100 bg-white">
            <table class="w-full min-w-[800px]">
                <x-table-header :headers="[['name'=>'IDENTITY','width'=>'25%'], ['name'=>'ROLE','width'=>'15%'], ['name'=>'ACTIONS','width'=>'35%'], ['name'=>'MANAGE','width'=>'25%']]" />
                
                <tbody class="divide-y divide-slate-100 bg-white">
                    @foreach($staffMembers as $member)
                        <tr class="hover:bg-slate-50 transition-all duration-200 text-center">
                            {{-- Identity Column --}}
                            <td class="px-4 py-8">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="text-[11px] text-slate-900 uppercase tracking-tight">{{ $member->name }}</span>
                                    <span class="text-[10px] text-slate-400 lowercase tracking-normal mt-0.5">{{ $member->email }}</span>
                                </div>
                            </td>

                            {{-- Role Column --}}
                            <td class="px-4 py-8">
                                <span class="text-[10px] uppercase tracking-[0.15em] {{ $member->hasRole('ADMIN') ? 'text-indigo-600 font-semibold' : 'text-slate-500' }}">
                                    {{ $member->getRoleNames()->first() }}
                                </span>
                            </td>

                            {{-- Actions/Permissions Column --}}
                            <td class="px-4 py-8">
                                <div class="flex flex-wrap gap-2 justify-center max-w-xs mx-auto">
                                    @if($member->hasRole('ADMIN'))
                                        <span class="text-[9px] text-emerald-600 uppercase tracking-widest bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100">FULL PRIVILEGES</span>
                                    @else
                                        @forelse($member->getPermissionNames() as $perm)
                                            <span class="text-[8px] text-slate-400 uppercase tracking-tighter bg-slate-50 px-2 py-1 rounded border border-slate-100">
                                                {{ strtoupper(str_replace(' ', '_', $perm)) }}
                                            </span>
                                        @empty
                                            <span class="text-[9px] text-rose-400 uppercase tracking-widest italic">RESTRICTED ACCESS</span>
                                        @endforelse
                                    @endif
                                </div>
                            </td>

                            {{-- Management Column --}}
                            <td class="px-4 py-8">
                                <div class="flex items-center justify-center gap-4">
                                    @if(Auth::id() !== $member->id)
                                        <x-secondary-button onclick="window.location='{{ route('admin.staff.edit', $member->id) }}'">
                                            EDIT
                                        </x-secondary-button>

                                        <form action="{{ route('admin.staff.destroy', $member->id) }}" method="POST" 
                                              onsubmit="return confirm('CRITICAL ACTION: Are you sure you want to remove {{ $member->name }}? This cannot be undone.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-[10px] text-rose-400 hover:text-rose-600 tracking-widest uppercase transition-all active:scale-95">
                                                REMOVE
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[9px] text-indigo-400 uppercase tracking-widest italic opacity-50 bg-indigo-50 px-4 py-2 rounded-lg">ACTIVE SESSION</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Architectural Pagination Footer --}}
        @if(method_exists($staffMembers, 'hasPages') && $staffMembers->hasPages())
            <div class="mt-10 px-4">
                <div class="flex flex-col md:flex-row justify-between items-center gap-6 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                    <div class="text-[10px] font-medium text-slate-400 uppercase tracking-widest text-center md:text-left">
                        Showing <span class="text-slate-900">{{ $staffMembers->firstItem() }}</span> 
                        to <span class="text-slate-900">{{ $staffMembers->lastItem() }}</span> 
                        of <span class="text-slate-900">{{ $staffMembers->total() }}</span> team members
                    </div>
                    
                    <div class="flex items-center gap-2">
                        {{-- Previous --}}
                        @if ($staffMembers->onFirstPage())
                            <button disabled class="px-4 py-2 text-[10px] text-slate-300 uppercase tracking-widest bg-slate-50 rounded-xl cursor-not-allowed">Previous</button>
                        @else
                            <x-secondary-button onclick="window.location='{{ $staffMembers->previousPageUrl() }}'" class="!text-[10px] !font-medium !px-4 !py-2 !rounded-xl uppercase">Previous</x-secondary-button>
                        @endif

                        {{-- Next --}}
                        @if ($staffMembers->hasMorePages())
                            <x-secondary-button onclick="window.location='{{ $staffMembers->nextPageUrl() }}'" class="!text-[10px] !font-medium !px-4 !py-2 !rounded-xl uppercase">Next</x-secondary-button>
                        @else
                            <button disabled class="px-4 py-2 text-[10px] text-slate-300 uppercase tracking-widest bg-slate-50 rounded-xl cursor-not-allowed">Next</button>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>