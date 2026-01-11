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
use Spatie\Permission\PermissionRegistrar;

class StaffController extends Controller
{
    public function index()
    {
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

        // Role column is gone, we just create the basic user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Spatie handles the relationship in the pivot tables
        $user->assignRole($request->role);

        return redirect()->route('admin.staff.index')->with('success', 'Staff account created.');
    }

    public function edit(User $staff)
    {
        $roles = \Spatie\Permission\Models\Role::where('name', '!=', 'CUSTOMER')->get();
        
        $permissions = \Spatie\Permission\Models\Permission::all();

        return view('admin.staff.edit', compact('staff', 'roles', 'permissions'));
    }

    public function update(Request $request, User $staff)
    {
        $request->validate([
            'permissions' => 'array',
            'role' => 'required'
        ]);

        // Update basic info
        $staff->update($request->only('name', 'email'));

        $staff->syncRoles([$request->role]);

        $staff->syncPermissions($request->permissions ?? []);

        return back()->with('success', 'Staff permissions updated. They can now perform authorized tracking actions.');
    }

    public function destroy(User $staff)
    {
        if (Auth::id() === $staff->id) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $staff->delete();
        
        return redirect()->route('admin.staff.index')->with('success', 'Staff member removed.');
    }
}