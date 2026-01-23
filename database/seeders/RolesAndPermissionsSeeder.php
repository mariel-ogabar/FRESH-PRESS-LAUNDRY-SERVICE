<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guardName = 'web';

        // 2. Define Permissions
        $permissions = [
            'manage staff',
            'manage services',       
            'create orders',         
            'update order status',
            'process payments',
            'cancel any order'
        ];

        // 3. Create Permissions safely
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => $guardName
            ]);
        }

        // 4. Create Roles and Assign Permissions
        
        // ADMIN: Gets everything
        $admin = Role::firstOrCreate(['name' => 'ADMIN', 'guard_name' => $guardName]);
        $admin->syncPermissions(Permission::all());

        // STAFF: Can handle orders and payments, but not system settings/staff
        $staff = Role::firstOrCreate(['name' => 'STAFF', 'guard_name' => $guardName]);
        $staff->syncPermissions([]);

        // CUSTOMER: Can only create their own orders
        $customer = Role::firstOrCreate(['name' => 'CUSTOMER', 'guard_name' => $guardName]);
        $customer->syncPermissions(['create orders']);

        // 5. Sync Existing Users (Logic adjustment to avoid 'role' column error)
        $users = User::all();
        foreach ($users as $user) {
            // Standardize: Remove existing roles to avoid duplicates
            $user->roles()->detach();

            // LOGIC: Identify roles based on email or other criteria 
            if ($user->email === 'admin@freshpress.com') {
                $user->assignRole($admin);
            } elseif (str_ends_with($user->email, '@freshpress.com')) {
                $user->assignRole($staff);
            } else {
                $user->assignRole($customer);
            }
        }
    }
}
