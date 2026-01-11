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

        // 3. Create Permissions safely
        $permissions = [
            'manage staff',
            'manage services',       
            'create orders',         
            'update order status',
            'process payments',
            'cancel any order'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => $guardName
            ]);
        }

        // 4. Create Roles and Assign Permissions
        $admin = Role::firstOrCreate(['name' => 'ADMIN', 'guard_name' => $guardName]);
        $admin->syncPermissions(Permission::all());

        $staff = Role::firstOrCreate(['name' => 'STAFF', 'guard_name' => $guardName]);
        
        // UPDATED: Give staff 'create orders' but NOT 'manage services' (pricing)
        $staff->syncPermissions([]);

        $customer = Role::firstOrCreate(['name' => 'CUSTOMER', 'guard_name' => $guardName]);

        // 5. Sync Existing Users
        $users = User::all();
        foreach ($users as $user) {
            $user->roles()->detach();

            if ($user->role === 'ADMIN') {
                $user->assignRole($admin);
            } elseif ($user->role === 'STAFF') {
                $user->assignRole($staff);
            } else {
                $user->assignRole($customer);
                if (empty($user->role)) {
                    $user->update(['role' => 'CUSTOMER']);
                }
            }
        }
    }
}