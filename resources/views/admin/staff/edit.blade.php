<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                PERMISSION SETTINGS: {{ strtoupper($staff->name) }}
            </h2>
            <a href="{{ route('admin.staff.index') }}" style="text-decoration: underline; color: black; font-weight: bold;">
                [ BACK TO LIST ]
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div style="border: 1px solid black; padding: 10px; margin-bottom: 20px; background-color: #f0fff4;">
                    <strong>SUCCESS:</strong> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div style="border: 1px solid red; color: red; padding: 10px; margin-bottom: 20px; background: #fee2e2;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white p-8" style="border: 1px solid black;">
                <form action="{{ route('admin.staff.update', $staff->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    {{-- Staff Basic Information --}}
                    <div style="margin-bottom: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <label style="display: block; font-weight: bold; margin-bottom: 5px;">NAME:</label>
                            <input type="text" name="name" value="{{ old('name', $staff->name) }}" required 
                                   style="width: 100%; border: 1px solid black; padding: 8px;">
                        </div>
                        <div>
                            <label style="display: block; font-weight: bold; margin-bottom: 5px;">EMAIL:</label>
                            <input type="email" name="email" value="{{ old('email', $staff->email) }}" required 
                                   style="width: 100%; border: 1px solid black; padding: 8px;">
                        </div>
                    </div>

                    {{-- Role Selection --}}
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: bold; margin-bottom: 5px; color: #4f46e5;">ASSIGN SYSTEM ROLE:</label>
                        <select name="role" required style="width: 100%; border: 1px solid black; padding: 8px; font-weight: bold;">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ $staff->hasRole($role->name) ? 'selected' : '' }}>
                                    {{ strtoupper($role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <hr style="border: 0; border-top: 1px solid black; margin-bottom: 20px;">

                    <p style="font-weight: bold; margin-bottom: 15px;">INDIVIDUAL PERMISSIONS (RESTRICTIONS):</p>
                    
                    {{-- Permissions Checkbox Grid --}}
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 30px;">
                        @foreach($permissions as $permission)
                            <div style="border: 1px solid black; padding: 10px; display: flex; align-items: center; gap: 10px;">
                                <input type="checkbox" 
                                       name="permissions[]" 
                                       value="{{ $permission->name }}" 
                                       id="perm_{{ $permission->id }}"
                                       style="width: 18px; height: 18px; cursor: pointer; border: 1px solid black;"
                                       {{-- Checks if the user specifically has this permission --}}
                                       {{ $staff->hasDirectPermission($permission->name) ? 'checked' : '' }}>
                                
                                <label for="perm_{{ $permission->id }}" style="cursor: pointer; font-size: 13px; font-weight: bold;">
                                    {{ strtoupper(str_replace('_', ' ', $permission->name)) }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <p style="font-size: 11px; color: #666; font-style: italic; margin-bottom: 20px;">
                        * Unchecking an action will override the Role and immediately restrict this user.
                    </p>

                    <div style="border-top: 1px solid black; padding-top: 20px; display: flex; gap: 15px;">
                        <button type="submit" style="background: black; color: white; border: none; padding: 10px 30px; font-weight: bold; cursor: pointer;">
                            UPDATE STAFF SETTINGS
                        </button>
                        
                        <a href="{{ route('admin.staff.index') }}" style="display: flex; align-items: center; text-decoration: underline; color: black; font-weight: bold;">
                            CANCEL
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>