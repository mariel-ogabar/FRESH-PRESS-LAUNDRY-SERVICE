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
        // 1. Admin Account
        User::create([
            'name' => 'Admin FreshPress',
            'email' => 'admin@freshpress.com',
            'password' => Hash::make('password123'),
            'role' => 'ADMIN',
        ]);

        // 2. Test Customer
        User::create([
            'name' => 'Test Customer',
            'email' => 'customer@freshpress.com',
            'password' => Hash::make('password'),
            'role' => 'CUSTOMER',
        ]);

        // 3. Services
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

        // 4. Add-Ons
        AddOn::create([
            'addon_name' => 'Express Service',
            'addon_price' => 250.00,
            'multiple_allowed' => false,
            'is_active' => true
        ]);
    }
}