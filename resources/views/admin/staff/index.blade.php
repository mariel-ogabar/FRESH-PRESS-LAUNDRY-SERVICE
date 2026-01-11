<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Staff Management') }}
            </h2>
            <a href="{{ route('admin.staff.create') }}" style="text-decoration: underline; font-weight: bold; color: black;">
                [ + ADD NEW STAFF ]
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div style="border: 1px solid black; padding: 10px; margin-bottom: 20px; background-color: #f0fff4;">
                    <strong>SUCCESS:</strong> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="border: 1px solid black; padding: 10px; margin-bottom: 20px; background-color: #fff5f5;">
                    <strong>ERROR:</strong> {{ session('error') }}
                </div>
            @endif

            <div class="bg-white p-6">
                <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
                    <thead>
                        <tr style="background-color: #f3f3f3;">
                            <th style="border: 1px solid black; padding: 10px; text-align: left;">NAME</th>
                            <th style="border: 1px solid black; padding: 10px; text-align: left;">ROLE</th>
                            <th style="border: 1px solid black; padding: 10px; text-align: left;">ALLOWED ACTIONS</th>
                            <th style="border: 1px solid black; padding: 10px; text-align: right;">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($staffMembers as $member)
                        <tr>
                            <td style="border: 1px solid black; padding: 10px;">{{ $member->name }}</td>
                            <td style="border: 1px solid black; padding: 10px;">{{ strtoupper($member->getRoleNames()->first() ?? 'No Role') }}</td>
                            
                            <td style="border: 1px solid black; padding: 10px; font-size: 11px;">
                                @if($member->hasRole('ADMIN'))
                                    <strong>[ FULL ACCESS ]</strong>
                                @else
                                    {{ strtoupper($member->getPermissionNames()->implode(', ')) ?: 'RESTRICTED' }}
                                @endif
                            </td>

                            <td style="border: 1px solid black; padding: 10px; text-align: right;">
                                @if(Auth::id() !== $member->id)
                                    <a href="{{ route('admin.staff.edit', $member->id) }}" style="text-decoration: underline; margin-right: 15px; color: black;">EDIT PERMS</a>
                                    
                                    <form action="{{ route('admin.staff.destroy', $member->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" style="text-decoration: underline; background: none; border: none; cursor: pointer; padding: 0; color: black;">
                                            REMOVE
                                        </button>
                                    </form>
                                @else
                                    <span style="font-style: italic; color: #666;">(Currently Logged In)</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>