<section>
    <p class="text-[11px] font-medium text-slate-500 uppercase tracking-tight mb-6">
        Once your account is deleted, all resources and data will be permanently removed.
    </p>

    <button 
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center px-4 py-2 bg-rose-50 text-rose-600 border border-rose-200 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-600 hover:text-white transition-all active:scale-95 shadow-sm"
    >
        {{ __('DELETE ACCOUNT') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8">
            @csrf
            @method('delete')

            <h2 class="text-sm font-black text-slate-800 uppercase tracking-widest">
                {{ __('Final Confirmation') }}
            </h2>

            <p class="mt-2 text-[11px] font-medium text-slate-500 uppercase">
                {{ __('Please enter your password to confirm permanent deletion.') }}
            </p>

            <div class="mt-6">
                <x-form-input label="Confirm Password" name="password" type="password" placeholder="PASSWORD" class="font-medium" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-8 flex justify-end gap-4 items-center">
                <button type="button" x-on:click="$dispatch('close')" class="text-[11px] font-black text-slate-400 uppercase tracking-widest">
                    Cancel
                </button>

                <x-primary-button class="!bg-rose-600 hover:!bg-rose-700 ring-rose-500/10">
                    {{ __('CONFIRM DELETE') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>
</section>