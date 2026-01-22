<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <x-form-input label="Current Password" name="current_password" type="password" class="font-medium" autocomplete="current-password" />
        <x-form-input label="New Password" name="password" type="password" class="font-medium" autocomplete="new-password" />
        <x-form-input label="Confirm Password" name="password_confirmation" type="password" class="font-medium" autocomplete="new-password" />

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button class="ring-4 ring-indigo-500/10 shadow-lg shadow-indigo-100">
                {{ __('UPDATE PASSWORD') }}
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest flex items-center gap-1">
                   <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                   Securely Updated
                </span>
            @endif
        </div>
    </form>
</section>