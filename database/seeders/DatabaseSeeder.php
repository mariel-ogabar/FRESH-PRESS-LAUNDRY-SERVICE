<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MainService;
use App\Models\AddOn;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Clear Spatie Cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Run Roles and Permissions Seeder first
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);

        // 2. Create Admin Account (Role column removed)
        $admin = User::create([
            'name' => 'Admin FreshPress',
            'email' => 'admin@freshpress.com',
            'password' => Hash::make('password123'),
        ]);
        $admin->assignRole('ADMIN');

        // 3. Create Test Customer
        $customer = User::create([
            'name' => 'Test Customer',
            'email' => 'customer@freshpress.com',
            'password' => Hash::make('password'),
        ]);
        $customer->assignRole('CUSTOMER');

        // 4. Create STAFF Account
        $staff = User::create([
            'name' => 'Staff Member',
            'email' => 'staff@freshpress.com',
            'password' => Hash::make('password'),
        ]);
        $staff->assignRole('STAFF');

        // 5. Main Services (Keep as is)
        MainService::create(['service_name' => 'Basic Wash & Fold', 'pricing_type' => 'PER_KG', 'service_base_price' => 150.00, 'is_active' => true]);
        MainService::create(['service_name' => 'Dry Cleaning', 'pricing_type' => 'PER_ITEM', 'service_base_price' => 350.00, 'is_active' => true]);
        MainService::create(['service_name' => 'Stain Removal Treatment', 'pricing_type' => 'PER_ITEM', 'service_base_price' => 200.00, 'is_active' => true]);

        // 6. Add-Ons (Keep as is)
        AddOn::create(['addon_name' => 'Express Service', 'addon_price' => 250.00, 'is_active' => true]);
        AddOn::create(['addon_name' => 'Delicate Care', 'addon_price' => 300.00, 'is_active' => true]);
        AddOn::create(['addon_name' => 'Ironing Service', 'addon_price' => 120.00, 'is_active' => true]);
    }
}