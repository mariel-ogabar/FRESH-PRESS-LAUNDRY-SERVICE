<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function index()
    {
        // Get only staff and admins, excluding customers
        $staffMembers = User::role(['ADMIN', 'STAFF'])->get();
        return view('admin.staff.index', compact('staffMembers'));
    }

    public function create()
    {
        $roles = Role::whereIn('name', ['ADMIN', 'STAFF'])->get();
        return view('admin.staff.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff account created successfully.');
    }

    public function edit(User $staff)
    {
        // Fetch roles excluding customers for the dropdown
        $roles = Role::where('name', '!=', 'CUSTOMER')->get();
        
        // Fetch all available permissions for the checkbox grid
        $permissions = Permission::all();

        return view('admin.staff.edit', compact('staff', 'roles', 'permissions'));
    }

    public function update(Request $request, User $staff)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $staff->id],
            'permissions' => 'array',
            'role' => 'required|exists:roles,name'
        ]);

        // 1. Update basic account info
        $staff->update($request->only('name', 'email'));

        // 2. Sync the Role
        $staff->syncRoles([$request->role]);

        // 3. Sync Direct Permissions (Manual Overrides)
        $staff->syncPermissions($request->permissions ?? []);

        // 4. CRITICAL: Clear Spatie Cache so changes apply immediately
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 5. REDIRECT to Index (as requested) with success message
        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff settings and permissions updated successfully.');
    }

    public function destroy(User $staff)
    {
        // Prevent self-deletion
        if (Auth::id() === $staff->id) {
            return back()->with('error', 'Security Alert: You cannot delete your own account.');
        }

        $staff->delete();
        
        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member has been removed from the system.');
    }
}