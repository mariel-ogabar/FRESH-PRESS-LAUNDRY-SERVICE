<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add New Staff Account') }}
            </h2>
            <a href="{{ route('admin.staff.index') }}" style="text-decoration: underline; font-weight: bold; color: black;">
                [ BACK TO LIST ]
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Error Display --}}
            @if ($errors->any())
                <div style="border: 1px solid black; padding: 10px; margin-bottom: 20px; background-color: #fff5f5;">
                    <ul style="margin: 0; padding-left: 20px; color: red;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div style="background: white; border: 1px solid black; padding: 30px;">
                <form action="{{ route('admin.staff.store') }}" method="POST">
                    @csrf
                    
                    <div style="display: flex; flex-direction: column; gap: 20px;">
                        
                        {{-- Name --}}
                        <div>
                            <label style="display: block; font-weight: bold; margin-bottom: 5px;">FULL NAME:</label>
                            <input type="text" name="name" value="{{ old('name') }}" required 
                                   style="width: 100%; border: 1px solid black; padding: 10px;">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label style="display: block; font-weight: bold; margin-bottom: 5px;">EMAIL ADDRESS:</label>
                            <input type="email" name="email" value="{{ old('email') }}" required 
                                   style="width: 100%; border: 1px solid black; padding: 10px;">
                        </div>

                        {{-- Role Selection --}}
                        <div>
                            <label style="display: block; font-weight: bold; margin-bottom: 5px; color: #4f46e5;">ASSIGN ROLE:</label>
                            <select name="role" required style="width: 100%; border: 1px solid black; padding: 10px; font-weight: bold;">
                                <option value="">-- SELECT ROLE --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ strtoupper($role->name) }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Password Grid --}}
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div>
                                <label style="display: block; font-weight: bold; margin-bottom: 5px;">PASSWORD:</label>
                                <input type="password" name="password" required 
                                       style="width: 100%; border: 1px solid black; padding: 10px;">
                            </div>
                            <div>
                                <label style="display: block; font-weight: bold; margin-bottom: 5px;">CONFIRM PASSWORD:</label>
                                <input type="password" name="password_confirmation" required 
                                       style="width: 100%; border: 1px solid black; padding: 10px;">
                            </div>
                        </div>

                    </div>

                    <div style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 20px; align-items: center;">
                        <a href="{{ route('admin.staff.index') }}" style="text-decoration: underline; color: black; font-weight: bold;">CANCEL</a>
                        <button type="submit" style="background: black; color: white; padding: 10px 25px; font-weight: bold; border: none; cursor: pointer;">
                            CREATE ACCOUNT
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>