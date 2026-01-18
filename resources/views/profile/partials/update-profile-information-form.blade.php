<section>
    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <x-form-input label="Full Name" name="name" type="text" :value="old('name', $user->name)" required autofocus class="font-medium" />
        
        <x-form-input label="Email Address" name="email" type="email" :value="old('email', $user->email)" required class="font-medium" />

        <x-form-input label="Contact Number" name="contact_no" type="text" :value="old('contact_no', $user->contact_no)" required class="font-medium" />

        <div>
            <label class="block text-[11px] font-black text-slate-700 uppercase tracking-widest mb-2 ml-1">Address</label>
            <textarea name="address" required class="w-full border-2 border-slate-100 rounded-xl p-3 text-sm font-medium text-slate-700 focus:border-indigo-500 focus:ring-0 transition-all uppercase placeholder:text-slate-300 min-h-[100px]">{{ old('address', $user->address) }}</textarea>
        </div>

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button class="ring-4 ring-indigo-500/10 shadow-lg shadow-indigo-100">
                {{ __('SAVE PROFILE') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                    Saved Successfully
                </span>
            @endif
        </div>
    </form>
</section>