<x-app-layout>

    <div class="py-12">

        <div class="flex flex-col items-center justify-center text-center gap-6 px-2 md:px-4 mb-16">
            {{-- Top: Centered Text --}}
            <div>
                <h2 class="font-normal text-3xl text-slate-700 uppercase tracking-[0.2em] leading-none">
                    {{ __('Account Settings') }}
                </h2>
                <p class="text-[11px] font-medium text-slate-400 uppercase tracking-[0.18em] mt-3">
                    {{ __('Manage your personal credentials and security preferences.') }}
                </p>
            </div>                

            {{-- Bottom: Centered Action Button --}}
            <div class="flex shrink-0">
                <x-primary-button 
                    onclick="window.location='{{ route('dashboard') }}'" 
                    class="!py-4 !px-10 !rounded-full !text-[11px] !font-black !uppercase !tracking-widest shadow-xl shadow-indigo-100 !bg-[#7c4dff] !text-white border-none transition-all hover:scale-105 active:scale-95"
                >
                    {{ __('BACK TO DASHBOARD') }}
                </x-primary-button>
            </div>
        </div>

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-10">
            
            {{-- Profile Information Section --}}
            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
                <div class="bg-slate-50 px-8 py-5 border-b border-slate-100">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Profile Information</span>
                </div>
                <div class="p-8 md:p-12 flex justify-center">
                    <div class="w-full max-w-2xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            {{-- Password Security Section --}}
            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
                <div class="bg-slate-50 px-8 py-5 border-b border-slate-100">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Security Update</span>
                </div>
                <div class="p-8 md:p-12 flex justify-center">
                    <div class="w-full max-w-2xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            {{-- Danger Zone Section --}}
            <div class="bg-white rounded-3xl shadow-xl border border-rose-100 overflow-hidden">
                <div class="bg-rose-50 px-8 py-5 border-b border-rose-100">
                    <span class="text-[10px] font-black text-rose-400 uppercase tracking-[0.2em]">Danger Zone</span>
                </div>
                <div class="p-8 md:p-12 flex justify-center">
                    <div class="w-full max-w-2xl text-center">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>