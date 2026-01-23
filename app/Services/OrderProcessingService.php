<?php

namespace App\Services;

use App\Models\User;
use App\Models\MainService;
use App\Models\AddOn;
use App\Models\OrderService;
use Illuminate\Support\Facades\Hash;

class OrderProcessingService
{
    // Calculate total price based on service, load size, and addons
    public function calculateTotal(int $serviceId, float $loadSize, ?array $addonIds): float
    {
        $mainService = MainService::findOrFail($serviceId);
        $baseTotal = $mainService->service_base_price * $loadSize;
        $addonsTotal = $addonIds ? AddOn::whereIn('id', $addonIds)->sum('addon_price') : 0;

        return $baseTotal + $addonsTotal;
    }

    // Attach services and addons to the order
    public function attachServicesAndAddons($order, array $validated, float $totalPrice): void
    {
        $addonPricesSum = isset($validated['addons'])
            ? AddOn::whereIn('id', $validated['addons'])->sum('addon_price')
            : 0;

        $orderService = OrderService::create([
            'order_id'      => $order->id,
            'service_id'    => $validated['service_id'],
            'quantity'      => $validated['load_size'],
            'service_price' => $totalPrice - $addonPricesSum,
        ]);

        if (!empty($validated['addons'])) {
            $addons = AddOn::whereIn('id', $validated['addons'])->get();
            foreach ($addons as $addon) {
                $orderService->addons()->attach($addon->id, [
                    'addon_qty'   => 1,
                    'addon_price' => $addon->addon_price
                ]);
            }
        }
    }

    // Get existing customer by email or create a new one
    public function getOrCreateCustomer(array $data)
    {
        $email = $data['email'] ?? null;
        if (!$email) throw new \InvalidArgumentException('Email required.');

        $user = User::withTrashed()->where('email', $email)->first();

        if ($user) {
            if ($user->trashed()) $user->restore();
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
            'password'   => Hash::make('FreshPress123'), 
            'contact_no' => $data['contact_no'] ?? null,
            'address'    => $data['address'] ?? null,
        ]);

        if (method_exists($newUser, 'assignRole')) {
            $newUser->assignRole('CUSTOMER');
        }

        return $newUser;
    }
}
