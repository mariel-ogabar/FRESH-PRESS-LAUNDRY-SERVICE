<?php

namespace App\Services;

use App\Models\User;
use App\Models\MainService;
use App\Models\AddOn;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OrderProcessingService
{
    public function calculateTotal(int $serviceId, float $loadSize, ?array $addonIds): float
    {
        $mainService = MainService::findOrFail($serviceId);
        $baseTotal = $mainService->service_base_price * $loadSize;
        $addonsTotal = $addonIds ? AddOn::whereIn('id', $addonIds)->sum('addon_price') : 0;

        return $baseTotal + $addonsTotal;
    }

    /**
     * Fetch existing customer or create a new one.
     * Updates details if the user already exists.
     */
    public function getOrCreateCustomer(array $data)
    {
        // 1. Search for user including soft-deleted ones to prevent unique constraint errors
        $user = User::withTrashed()->where('email', $data['email'])->first();

        if ($user) {
            // Restore if they were deleted
            if ($user->trashed()) {
                $user->restore();
            }

            // 2. Update existing user details if they provided new info during walk-in
            $user->update([
                'contact_no' => $data['contact_no'] ?? $user->contact_no,
                'address'    => $data['address'] ?? $user->address,
            ]);

            return $user;
        }

        // 3. Create new user if not found
        $newUser = User::create([
            'name'       => $data['customer_name'] ?? 'Walk-in Customer',
            'email'      => $data['email'],
            'password'   => Hash::make('FreshPress123'), 
            'contact_no' => $data['contact_no'] ?? null,
            'address'    => $data['address'] ?? null,
        ]);

        // Assign Spatie Role if using laravel-permission
        if (method_exists($newUser, 'assignRole')) {
            $newUser->assignRole('CUSTOMER');
        }

        return $newUser;
    }
}