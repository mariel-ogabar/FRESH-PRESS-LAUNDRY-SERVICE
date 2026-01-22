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
        $email = $data['email'] ?? null;

        if (!$email) {
            throw new \InvalidArgumentException('The email field is required to process or identify a customer.');
        }

        $user = User::withTrashed()->where('email', $email)->first();

        if ($user) {
            if ($user->trashed()) {
                $user->restore();
            }

            $user->update([
                'name'       => $data['customer_name'] ?? $user->name,
                'contact_no' => $data['contact_no'] ?? $user->contact_no,
                'address'    => $data['address'] ?? $user->address,
            ]);

            return $user;
        }

        $newUser = User::create([
            'name'       => $data['customer_name'] ?? 'Walk-in Customer',
            'email'      => $email,
            'password'   => \Illuminate\Support\Facades\Hash::make('FreshPress123'), 
            'contact_no' => $data['contact_no'] ?? null,
            'address'    => $data['address'] ?? null,
        ]);

        if (method_exists($newUser, 'assignRole')) {
            $newUser->assignRole('CUSTOMER');
        }

        return $newUser;
    }
}