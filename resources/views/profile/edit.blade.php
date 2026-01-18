<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-xl text-gray-800 leading-tight uppercase tracking-tighter">
                    {{ __('Account Settings') }}
                </h2>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-tight mt-1">
                    {{ __('Manage your personal credentials and security preferences.') }}
                </p>
            </div>
            
            <x-secondary-button :href="route('dashboard')">
                <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('BACK TO DASHBOARD') }}
            </x-secondary-button>
        </div>
    </x-slot>

    <div class="py-12">
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