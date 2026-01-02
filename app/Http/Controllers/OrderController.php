<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\MainService;
use App\Models\AddOn;
use App\Models\LaundryStatus;
use App\Models\LaundryStatusAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:main_services,id',
            'items.*.quantity' => 'required|numeric|min:0.1',
            'items.*.addons' => 'nullable|array',
            'items.*.addons.*.addon_id' => 'required|exists:add_ons,id',
            'items.*.addons.*.qty' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($validated) {
            $order = Order::create([
                'user_id' => $validated['user_id'],
                'total_price' => 0,
                'order_status' => 'ACTIVE',
            ]);

            $grandTotal = 0;

            foreach ($validated['items'] as $item) {
                $service = MainService::find($item['service_id']);
                $subtotalService = $service->service_base_price * $item['quantity'];

                $orderService = $order->services()->create([
                    'service_id' => $service->id,
                    'quantity' => $item['quantity'],
                    'service_price' => $subtotalService,
                ]);

                $grandTotal += $subtotalService;

                if (!empty($item['addons'])) {
                    foreach ($item['addons'] as $addonData) {
                        $addon = AddOn::find($addonData['addon_id']);
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

            $order->update(['total_price' => $grandTotal]);

            $order->laundryStatus()->create(['current_status' => 'PENDING']);
            $order->payment()->create([
                'payment_method' => 'CASH', 
                'payment_status' => 'PENDING'
            ]);

            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order->load('services.addons', 'laundryStatus')
            ], 201);
        });
    }

    /**
     * Update the laundry status for a specific order.
     * This fires the SQL Trigger automatically.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'current_status' => 'required|in:PENDING,WASHING,DRYING,FOLDING,IRONING,READY'
        ]);

        // Find the status record for the specific order
        $status = LaundryStatus::where('order_id', $id)->firstOrFail();
        
        // Update the status
        $status->update([
            'current_status' => $request->current_status
        ]);

        return response()->json([
            'message' => 'Status updated to ' . $request->current_status,
            // Prove the Phase 5 Trigger worked by returning the audit history
            'history' => LaundryStatusAudit::where('order_id', $id)->get()
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
        // Because of 'onDelete("cascade")' in your migrations, 
        // related payments, statuses, and items are deleted automatically.
        $order->delete();

        return response()->json([
            'message' => 'Order #'.$id.' and all associated data have been deleted.'
        ], 200);
    }
}