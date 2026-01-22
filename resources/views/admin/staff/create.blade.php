<x-app-layout>

    <div class="py-8 px-4 md:px-10 max-w-[90rem] mx-auto">

        <div class="flex flex-col items-center justify-center text-center gap-6 px-2 md:px-4 mb-16">
            <div>
                <h2 class="font-normal text-3xl text-slate-700 uppercase tracking-widest leading-none">
                    {{ __('Staff Registration') }}
                </h2>
                <p class="text-[11px] font-medium text-slate-400 uppercase tracking-[0.18em] mt-3">
                    {{ __('Initialize a new team member and define system access levels.') }}
                </p>
            </div>                

            <div class="flex shrink-0">
                <x-primary-button 
                    onclick="window.location='{{ route('admin.staff.index') }}'" 
                    class="!py-4 !px-10 !rounded-full !text-[11px] !font-black !uppercase !tracking-widest shadow-xl shadow-indigo-100 !bg-[#7c4dff] !text-white border-none transition-all hover:scale-105 active:scale-95"
                >
                    {{ __('BACK TO LIST') }}
                </x-primary-button>
            </div>
        </div>
        <div class="max-w-3xl mx-auto space-y-8">
            
            {{-- Validation Feedback --}}
            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-100 p-5 rounded-2xl flex flex-col gap-3 shadow-sm animate-fade-in">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <span class="text-[10px] font-semibold text-rose-700 uppercase tracking-widest">Initialization Errors Detected:</span>
                    </div>
                    <ul class="text-[10px] font-medium text-rose-500 uppercase list-none space-y-1 ml-6">
                        @foreach ($errors->all() as $error)
                            <li>— {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-[2rem] shadow-2xl border border-slate-100 overflow-hidden">
                <div class="bg-slate-50/50 px-8 py-6 border-b border-slate-100 text-center">
                    <span class="block text-[13px] font-bold text-black uppercase tracking-widest mb-2 ml-1">
                        New Credential Profile
                    </span>
                </div>

                <form action="{{ route('admin.staff.store') }}" method="POST" class="p-8 md:p-12 space-y-12" autocomplete="off">
                    @csrf
                    
                    {{-- Identity Section --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-8">
                        <div class="space-y-2">
                            <x-form-input label="Identity Name" name="name" value="" required placeholder="FULL NAME" class="!font-medium uppercase !rounded-xl" autocomplete="new-password" />
                        </div>
                        <div class="space-y-2">
                            <x-form-input label="System Email" type="email" name="email" value="" required placeholder="EMAIL@FRESHPRESS.COM" class="!font-medium !rounded-xl" autocomplete="new-password" />
                        </div>
                    </div>

                    {{-- Role Selection --}}
                    <div class="py-4">
                        <label class="block text-[11px] font-medium text-slate-400 uppercase tracking-[0.2em] mb-8 text-center">Functional Role Assignment</label>
                        <div class="flex flex-col sm:flex-row justify-center items-center gap-8 sm:gap-16">
                            <label class="flex items-center gap-4 cursor-pointer group">
                                <input type="radio" name="role" value="ADMIN" required
                                       class="w-5 h-5 text-indigo-600 border-2 border-slate-200 focus:ring-indigo-500/20 focus:ring-offset-0 transition-all cursor-pointer">
                                <span class="text-[11px] font-medium uppercase tracking-widest text-slate-400 group-hover:text-indigo-600 transition-colors">
                                    ADMIN
                                </span>
                            </label>

                            <label class="flex items-center gap-4 cursor-pointer group">
                                <input type="radio" name="role" value="STAFF" required
                                       class="w-5 h-5 text-indigo-600 border-2 border-slate-200 focus:ring-indigo-500/20 focus:ring-offset-0 transition-all cursor-pointer">
                                <span class="text-[11px] font-medium uppercase tracking-widest text-slate-400 group-hover:text-indigo-600 transition-colors">
                                    STAFF
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="h-px bg-slate-50 w-full"></div>

                    {{-- Security Section --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-8">
                        <x-form-input label="Access Password" type="password" name="password" value="" required class="!font-medium !rounded-xl" autocomplete="new-password" />
                        <x-form-input label="Verify Password" type="password" name="password_confirmation" value="" required class="!font-medium !rounded-xl" autocomplete="new-password" />
                    </div>

                    {{-- Footer Actions --}}
                    <div class="pt-10 border-t border-slate-50 flex flex-col sm:flex-row items-center justify-between gap-6">
                        <a href="{{ route('admin.staff.index') }}" 
                           class="text-[10px] font-medium uppercase tracking-[0.2em] text-slate-300 hover:text-rose-500 transition-colors order-2 sm:order-1">
                            CANCEL INITIALIZATION
                        </a>
                        
                        <x-primary-button type="submit">
                            {{ __('INITIALIZE ACCOUNT') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>