<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderService;
use App\Models\MainService;
use App\Models\AddOn;
use App\Models\LaundryStatus;
use App\Models\LaundryStatusAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OrderController extends Controller
{
    /**
     * Store a newly created order (Includes Walk-in Logic + Services + Addons).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Customer Info (Walk-in Logic)
            'email' => 'required|email',
            'name' => 'required_if:is_new,true',
            'contact_no' => 'nullable|string',
            'address' => 'nullable|string',
            
            // Order Items
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:main_services,id',
            'items.*.quantity' => 'required|numeric|min:0.1',
            'items.*.addons' => 'nullable|array',
            'items.*.addons.*.addon_id' => 'required|exists:add_ons,id',
            'items.*.addons.*.qty' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            
            // 1. "Check or Create" User (Objective: User Authentication)
            $user = User::where('email', $validated['email'])->first();

            if (!$user) {
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make('freshpress123'), // Default password
                    'role' => 'CUSTOMER',
                    'contact_no' => $validated['contact_no'] ?? null,
                    'address' => $validated['address'] ?? null,
                ]);
            }

            // 2. Create the Order Header
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => 0,
                'order_status' => 'ACTIVE',
            ]);

            $grandTotal = 0;

            // 3. Process Services and Add-ons (Objective: Eloquent ORM)
            foreach ($validated['items'] as $item) {
                $service = MainService::findOrFail($item['service_id']);
                $subtotalService = $service->service_base_price * $item['quantity'];

                $orderService = $order->services()->create([
                    'service_id' => $service->id,
                    'quantity' => $item['quantity'],
                    'service_price' => $subtotalService,
                ]);

                $grandTotal += $subtotalService;

                if (!empty($item['addons'])) {
                    foreach ($item['addons'] as $addonData) {
                        $addon = AddOn::findOrFail($addonData['addon_id']);
                        $subtotalAddon = $addon->addon_price * $addonData['qty'];

                        $orderService->addons()->create([
                            'addon_id' => $addon->id,
                            'addon_qty' => $addonData['qty'],
                            'addon_price' => $subtotalAddon,
                        ]);

                        $grandTotal += $subtotalAddon;
                    }
                }
            }

            // 4. Finalize Order and Lifecycle (Objective: Scalability)
            $order->update(['total_price' => $grandTotal]);

            $order->laundryStatus()->create(['current_status' => 'PENDING']);
            
            $order->payment()->create([
                'payment_method' => 'CASH', 
                'payment_status' => 'PENDING'
            ]);

            return response()->json([
                'message' => 'Order created successfully for ' . $user->name,
                'order' => $order->load('services.addons', 'laundryStatus', 'user')
            ], 201);
        });
    }

    /**
     * Update status & Prove Phase 5 SQL Trigger.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'current_status' => 'required|in:PENDING,WASHING,DRYING,FOLDING,IRONING,READY'
        ]);

        $status = LaundryStatus::where('order_id', $id)->firstOrFail();
        $status->update(['current_status' => $request->current_status]);

        return response()->json([
            'message' => 'Status updated successfully',
            'audit_trail' => LaundryStatusAudit::where('order_id', $id)->get()
        ]);
    }

    /**
     * Display a listing of all orders (Read All).
     */
    public function index()
    {
        // Eager load the user and laundry status for the dashboard
        return response()->json(Order::with(['user', 'laundryStatus'])->get());
    }

    /**
     * Display the specified order (Read Single).
     */
    public function show($id)
    {
        // Eager load nested services and addons for a detailed view
        $order = Order::with(['services.addons', 'laundryStatus', 'user', 'payment'])->findOrFail($id);
        return response()->json($order);
    }

    /**
     * Remove the specified order from storage (Delete).
     */
    public function destroy($id)
    {
        // 1. Find the order
        $order = Order::findOrFail($id);

        // 2. Perform the delete
        $order->delete();

        return response()->json([
            'message' => 'Order #'.$id.' and all associated data have been deleted.'
        ], 200);
    }
}