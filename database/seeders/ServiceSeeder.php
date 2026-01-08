<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MainService;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        // 2. Add Services (Name, Price, and Active status ONLY)
        $services = [
            [
                'service_name' => 'Wash & Fold (Regular)',
                'pricing_type' => 'PER_KG',
                'service_base_price' => 35.00, 
                'is_active' => true,
            ],
            [
                'service_name' => 'Dry Cleaning',
                'pricing_type' => 'PER_ITEM',
                'service_base_price' => 150.00,
                'is_active' => true,
            ],
            [
                'service_name' => 'Comforter / Bedding',
                'pricing_type' => 'PER_ITEM',
                'service_base_price' => 200.00,
                'is_active' => true,
            ],
            [
                'service_name' => 'Ironing / Pressing',
                'pricing_type' => 'PER_ITEM',
                'service_base_price' => 45.00,
                'is_active' => true,
            ],
            [
                'service_name' => 'Shoe Cleaning',
                'pricing_type' => 'PER_ITEM',
                'service_base_price' => 250.00,
                'is_active' => true,
            ],
        ];

        // 3. Save to database
        foreach ($services as $service) {
            MainService::updateOrCreate(
                ['service_name' => $service['service_name']],
                $service
            );
        }
    }
}