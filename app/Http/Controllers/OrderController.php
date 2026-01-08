<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderService;
use App\Models\AddOn;
use App\Models\User;
use App\Models\MainService;
use App\Services\OrderProcessingService;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateStatusRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderProcessingService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Display the booking form.
     */
    public function create(Request $request)
    {
        $authUser = Auth::user();
        
        $services = MainService::where('is_active', true)->get();
        $addons = AddOn::where('is_active', true)->get();

        $foundUser = null;
        if ($authUser->role === 'ADMIN' && $request->filled('email')) {
            $foundUser = User::where('email', $request->email)
                ->where('role', 'CUSTOMER')
                ->first();
        }

        return view('orders.create', compact('services', 'addons', 'authUser', 'foundUser'));
    }

    /**
     * Store a newly created order.
     */
    public function store(StoreOrderRequest $request) 
    {
        $authUser = Auth::user();

        return DB::transaction(function () use ($request, $authUser) {
            
            $customer = ($authUser->role === 'ADMIN') 
                ? $this->orderService->getOrCreateCustomer($request->validated()) 
                : $authUser;

            $totalPrice = $this->orderService->calculateTotal(
                $request->service_id,
                (float) $request->load_size,
                $request->addons
            );

            // Create Order
            $order = Order::create([
                'user_id' => $customer->id,
                'total_price' => $totalPrice,
                'order_status' => Order::STATUS_ACTIVE,
            ]);

            // Create Order Service Details
            $addonPricesSum = $request->has('addons') 
                ? AddOn::whereIn('id', $request->addons)->sum('addon_price') 
                : 0;

            $orderService = OrderService::create([
                'order_id' => $order->id,
                'service_id' => $request->service_id,
                'quantity' => $request->load_size,
                'service_price' => $totalPrice - $addonPricesSum,
            ]);

            // Attach Add-ons
            if ($request->has('addons')) {
                foreach ($request->addons as $addonId) {
                    $addon = AddOn::find($addonId);
                    $orderService->addons()->attach($addonId, [
                        'addon_qty' => 1,
                        'addon_price' => $addon->addon_price
                    ]);
                }
            }

            // Initialize Status, Collection, Delivery, Payment
            $this->initializeOrderDetails($order, $request);

            return redirect()->route('dashboard')->with('success', 'Booking Confirmed!');
        });
    }

    /**
     * Status Update Methods (Consistent JSON Responses)
     */
    public function updateCollection(UpdateStatusRequest $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->collection->update(['collection_status' => $request->collection_status]);
        return response()->json(['message' => 'Collection updated'], 200);
    }

    public function updateStatus(UpdateStatusRequest $request, $id)
    {
        $order = Order::findOrFail($id);
        $this->authorize('updateStatus', Order::class);

        try {
            $oldStatus = $order->laundryStatus->current_status;
            $newStatus = $request->current_status;

            if ($oldStatus !== $newStatus) {
                $order->laundryStatus->update(['current_status' => $newStatus]);

                $order->audits()->create([
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'changed_at' => now(),
                ]);
            }

            return response()->json(['message' => 'Status updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error'], 500);
        }
    }

    public function updatePayment(UpdateStatusRequest $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $data = ['payment_status' => $request->payment_status];
        
        if ($request->payment_status === \App\Models\Payment::STATUS_PAID) {
            $data['payment_date'] = now();
        } else {
            $data['payment_date'] = null; // I-reset kung binalik sa Pending
        }

        $order->payment->update($data);
        return response()->json(['message' => 'Payment updated'], 200);
    }

    public function updateDelivery(UpdateStatusRequest $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $delivery = $order->delivery;

        if ($request->delivery_status === 'DELIVERED') {
            $delivery->delivery_status = 'DELIVERED';
            $delivery->delivered_date = now(); 
            $order->update(['order_status' => Order::STATUS_COMPLETED]);
        } else {
            $delivery->delivery_status = $request->delivery_status;
        }

        $delivery->save();

        return response()->json(['message' => 'Status updated'], 200);
    }

    public function setDeliverySchedule(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $order->delivery->update([
            'scheduled_delivery_date' => $request->scheduled_date 
        ]);
        
        return response()->json(['message' => 'Schedule updated'], 200);
    }

    public function cancel($id)
    {
        $order = Order::findOrFail($id);
        $this->authorize('cancel', $order);

        return DB::transaction(function () use ($order) {
            $order->update(['order_status' => Order::STATUS_CANCELLED]);
            $order->delete(); 
            return redirect()->route('dashboard')->with('success', 'Order successfully cancelled.');
        });
    }

    private function initializeOrderDetails($order, $request)
    {
        $order->laundryStatus()->create(['current_status' => 'PENDING']);
        
        $collectionDate = ($request->collection_method === 'STAFF_PICKUP') 
            ? $request->collection_date . ' ' . $request->collection_time 
            : now();

        $order->collection()->create([
            'collection_method' => $request->collection_method,
            'collection_status' => 'PENDING',
            'collection_date' => $collectionDate,
        ]);

        $order->delivery()->create([
            'delivery_method' => $request->return_method === 'DELIVERY' ? 'DELIVERY' : 'STORE_PICKUP',
            'delivery_status' => 'READY',
        ]);

        $order->payment()->create([
            'payment_method' => 'CASH',
            'payment_status' => 'PENDING',
        ]);
    }
}