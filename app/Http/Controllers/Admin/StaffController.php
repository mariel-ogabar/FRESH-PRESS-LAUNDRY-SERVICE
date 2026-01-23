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
    // Display a list of all staff members (ADMIN and STAFF roles)
    public function index()
    {
        $staffMembers = User::role(['ADMIN', 'STAFF'])->get();
        return view('admin.staff.index', compact('staffMembers'));
    }

    // Show the form for creating a new staff member
    public function create()
    {
        $roles = Role::whereIn('name', ['ADMIN', 'STAFF'])->get();
        return view('admin.staff.create', compact('roles'));
    }

    // Store a newly created staff member in the database
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

    //  Show the form for editing an existing staff member
    public function edit(User $staff)
    {
        $roles = Role::where('name', '!=', 'CUSTOMER')->get();
        
        $permissions = Permission::all();

        return view('admin.staff.edit', compact('staff', 'roles', 'permissions'));
    }

    // Update the specified staff member in the database
    public function update(Request $request, User $staff)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $staff->id],
            'permissions' => 'array',
            'role' => 'required|exists:roles,name'
        ]);

        $staff->update($request->only('name', 'email'));

        $staff->syncRoles([$request->role]);

        $staff->syncPermissions($request->permissions ?? []);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff settings and permissions updated successfully.');
    }

    // Remove the specified staff member from the database
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
