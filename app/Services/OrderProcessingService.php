<?php

namespace App\Services;

use App\Models\User;
use App\Models\MainService;
use App\Models\AddOn;
use Illuminate\Support\Facades\Hash;

class OrderProcessingService
{
    public function calculateTotal(int $serviceId, float $loadSize, ?array $addonIds): float
    {
        $mainService = MainService::findOrFail($serviceId);
        $baseTotal = $mainService->service_base_price * $loadSize;
        $addonsTotal = $addonIds ? AddOn::whereIn('id', $addonIds)->sum('addon_price') : 0;

        return $baseTotal + $addonsTotal;
    }

    public function getOrCreateCustomer(array $data)
    {
        return \App\Models\User::firstOrCreate(
            ['email' => $data['email']],
            [
                'name'       => $data['customer_name'], 
                'role'       => 'CUSTOMER',
                'password'   => \Illuminate\Support\Facades\Hash::make('password123'),
                'contact_no' => $data['contact_no'] ?? null,
                'address'    => $data['address'] ?? null,
            ]
        );
    }
}