
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MainService;
use App\Models\AddOn;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create a test Customer
        User::create([
            'name' => 'Test Customer',
            'email' => 'customer@freshpress.com',
            'password' => Hash::make('password'),
            'role' => 'CUSTOMER',
        ]);

        // --- MAIN SERVICES  ---
        MainService::create([
            'service_name' => 'Basic Wash & Fold',
            'pricing_type' => 'PER_KG',
            'service_base_price' => 150.00, 
            'is_active' => true
        ]);

        MainService::create([
            'service_name' => 'Dry Cleaning',
            'pricing_type' => 'PER_ITEM',
            'service_base_price' => 350.00, 
            'is_active' => true
        ]);

        MainService::create([
            'service_name' => 'Ironing Service',
            'pricing_type' => 'PER_KG',
            'service_base_price' => 120.00,
            'is_active' => true
        ]);

        MainService::create([
            'service_name' => 'Stain Removal Treatment',
            'pricing_type' => 'PER_ITEM',
            'service_base_price' => 200.00, 
            'is_active' => true
        ]);

        // --- ADD-ONS ---
        AddOn::create([
            'addon_name' => 'Express Service',
            'addon_price' => 250.00,
            'multiple_allowed' => false,
            'is_active' => true
        ]);

        AddOn::create([
            'addon_name' => 'Delicate Care',
            'addon_price' => 300.00,
            'multiple_allowed' => false,
            'is_active' => true
        ]);
    }
}
