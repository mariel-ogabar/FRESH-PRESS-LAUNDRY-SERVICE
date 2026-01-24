<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\MainService;
use App\Models\AddOn;
use App\Models\OrderService;
use App\Models\LaundryStatus;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class OrderLifecycleSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch existing data to link to
        $customer = User::role('CUSTOMER')->first();
        $staff = User::role('STAFF')->first();
        $services = MainService::all();
        $addons = AddOn::all();

        if (!$customer || $services->isEmpty()) return;

        // Define different stages to distribute across dummy data
        $stages = [
            ['status' => LaundryStatus::READY,   'pay' => Payment::STATUS_PAID,    'count' => 5],
            ['status' => LaundryStatus::WASHING, 'pay' => Payment::STATUS_PENDING, 'count' => 3],
            ['status' => LaundryStatus::PENDING, 'pay' => Payment::STATUS_PENDING, 'count' => 4],
            ['status' => LaundryStatus::IRONING, 'pay' => Payment::STATUS_PAID,    'count' => 2],
        ];

        foreach ($stages as $stage) {
            for ($i = 0; $i < $stage['count']; $i++) {
                DB::transaction(function () use ($customer, $services, $addons, $stage) {
                    $selectedService = $services->random();
                    $quantity = rand(3, 10);
                    $baseTotal = $selectedService->service_base_price * $quantity;
                    
                    // Create the Order
                    $order = Order::create([
                        'user_id' => $customer->id,
                        'total_price' => $baseTotal,
                        'order_status' => Order::STATUS_ACTIVE,
                        'created_at' => now()->subDays(rand(1, 10)),
                    ]);

                    // Attach the Service details
                    OrderService::create([
                        'order_id' => $order->id,
                        'service_id' => $selectedService->id,
                        'quantity' => $quantity,
                        'service_price' => $baseTotal,
                    ]);

                    // Initialize the Progress, Collection, Delivery, and Payment tables
                    $order->laundryStatus()->create(['current_status' => $stage['status']]);
                    
                    $order->collection()->create([
                        'collection_method' => 'DROP_OFF',
                        'collection_status' => 'RECEIVED',
                        'collection_date' => now()->subDays(1),
                    ]);

                    $order->delivery()->create([
                        'delivery_method' => 'STORE_PICKUP',
                        'delivery_status' => $stage['status'] === LaundryStatus::READY ? 'READY' : 'PENDING',
                    ]);

                    $order->payment()->create([
                        'payment_method' => 'CASH',
                        'payment_status' => $stage['pay'],
                        'payment_date' => $stage['pay'] === Payment::STATUS_PAID ? now() : null,
                    ]);
                });
            }
        }
    }
}