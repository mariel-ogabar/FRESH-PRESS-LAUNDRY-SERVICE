<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderService;
use App\Models\Delivery;
use App\Models\AddOn;
use App\Models\User;
use App\Models\MainService;
use App\Models\Payment;
use App\Services\OrderProcessingService;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateStatusRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Database\Eloquent\Builder;

class OrderController extends Controller
{
    use AuthorizesRequests;

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
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();
        
        // 1. Permission Check
        if (!$authUser->hasAnyRole(['ADMIN', 'STAFF', 'CUSTOMER']) && !$authUser->can('create orders')) {
            abort(403);
        }

        $services = MainService::where('is_active', true)->get();
        $addons = AddOn::where('is_active', true)->get();

        // 2. Walk-in Customer Lookup
        $foundUser = null;
        if (($authUser->hasAnyRole(['ADMIN', 'STAFF'])) && $request->filled('email')) {
            $foundUser = User::where('email', $request->email)->first();
        }

        return view('orders.create', compact('services', 'addons', 'authUser', 'foundUser'));
    }

    public function show($id)
    {
        // Eager load everything needed for the tracking view
        $order = Order::with([
            'user', 
            'laundryStatus', 
            'audits' => function($query) {
                $query->orderBy('changed_at', 'desc');
            }, 
            'payment', 
            'delivery', 
            'collection', 
            'services.mainService'
        ])->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    /**
     * Store a newly created order.
     */
    public function store(StoreOrderRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $authUser = Auth::user();
            $validated = $request->validated();

            // 1. Resolve Customer
            $customer = $this->resolveCustomer($authUser, $validated, $request->email);

            // 2. Pricing
            $totalPrice = $this->orderService->calculateTotal(
                $validated['service_id'],
                (float) $validated['load_size'],
                $validated['addons'] ?? []
            );

            // 3. Create Order
            $order = Order::create([
                'user_id' => $customer->id,
                'total_price' => $totalPrice,
                'order_status' => Order::STATUS_ACTIVE,
            ]);

            // 4. Attach Items
            $this->orderService->attachServicesAndAddons($order, $validated, $totalPrice);

            // 5. Initialize Details (Sets Delivery to PENDING)
            $this->initializeOrderDetails($order, $request);

            return redirect()->route('dashboard')->with('success', 'Booking Confirmed! Order #' . $order->id);
        });
    }

    // Resolve Customer based on Auth User and Input
    private function resolveCustomer($authUser, $validated, $email)
    {
        if ($authUser->hasAnyRole(['ADMIN', 'STAFF']) || $authUser->can('create orders')) {
            return $this->orderService->getOrCreateCustomer(array_merge($validated, ['email' => $email]));
        }

        $authUser->update(array_filter([
            'contact_no' => $validated['contact_no'] ?? $authUser->contact_no,
            'address'    => $validated['address'] ?? $authUser->address,
        ]));

        return $authUser;
    }
    /**
     * Update Laundry Stage 
     */
    public function updateStatus(UpdateStatusRequest $request, $id)
    {
        $order = Order::withoutGlobalScope('own_orders')->findOrFail($id);
        
        $this->authorize('updateStatus', Order::class);

        try {
            $oldStatus = $order->laundryStatus->current_status;
            $newStatus = $request->current_status;

            if ($oldStatus !== $newStatus) {
                $order->laundryStatus->update(['current_status' => $newStatus]);
                
                return response()->json(['message' => 'Status updated successfully'], 200);
            }

            return response()->json(['message' => 'No status change detected'], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => config('app.debug') ? $e->getMessage() : 'Error processing update'
            ], 500);
        }
    }

    /**
     * Update Collection Status
     */
    public function updateCollection(UpdateStatusRequest $request, $id)
    {
        if (!Auth::user()->can('update order status')) {
            return response()->json(['message' => 'Permission Denied.'], 403);
        }

        $order = Order::findOrFail($id);
        $order->collection->update(['collection_status' => $request->collection_status]);
        return response()->json(['message' => 'Collection updated'], 200);
    }

    /**
     * Set Collection Schedule (Staff Pickup)
     */
    public function setCollectionSchedule(Request $request, $id)
    {
        if (!Auth::user()->can('update order status')) {
            return response()->json(['message' => 'Permission Denied'], 403);
        }

        $request->validate([
            'scheduled_date' => 'required|date'
        ]);

        $order = Order::findOrFail($id);
        
        $order->collection->update([
            'scheduled_collection_date' => $request->scheduled_date
        ]);
        
        return response()->json(['message' => 'Pickup schedule updated'], 200);
    }

    /**
     * Update Payment Status
     */
    public function updatePayment(UpdateStatusRequest $request, $id)
    {
        if (!Auth::user()->can('process payments')) {
            return response()->json(['message' => 'Permission Denied.'], 403);
        }

        $order = Order::findOrFail($id);
        $data = ['payment_status' => $request->payment_status];
       
        if ($request->payment_status === \App\Models\Payment::STATUS_PAID) {
            $data['payment_date'] = now();
        } else {
            $data['payment_date'] = null;
        }

        $order->payment->update($data);
        return response()->json(['message' => 'Payment updated'], 200);
    }

    /**
     * Update Delivery Status
     */
    public function updateDelivery(UpdateStatusRequest $request, $id)
    {
        if (!Auth::user()->can('update order status')) {
            return response()->json(['message' => 'Permission Denied'], 403);
        }

        $order = Order::findOrFail($id);
        $delivery = $order->delivery;

        if ($request->delivery_status === Delivery::STATUS_DELIVERED) {
            $delivery->delivery_status = Delivery::STATUS_DELIVERED;
            $delivery->delivered_date = now();
            $order->update(['order_status' => Order::STATUS_COMPLETED]);
        } else {
            $delivery->delivery_status = $request->delivery_status;
            $delivery->delivered_date = null;
            if ($order->order_status === Order::STATUS_COMPLETED) {
                $order->update(['order_status' => Order::STATUS_ACTIVE]);
            }
        }

        $delivery->save();
        return response()->json(['message' => 'Status updated'], 200);
    }
    /**
     * Set Delivery Schedule
     */
    public function setDeliverySchedule(Request $request, $id)
    {
        if (!Auth::user()->can('update order status')) {
            return response()->json(['message' => 'Permission Denied'], 403);
        }

        $order = Order::findOrFail($id); 
        $order->delivery->update([
            'scheduled_delivery_date' => $request->scheduled_date
        ]);
       
        return response()->json(['message' => 'Schedule updated'], 200);
    }

    /**
     * Cancel Order
     */
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

    // Initialize Order Details: Laundry Status, Collection, Delivery, Payment
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
            'delivery_status' => 'PENDING',
        ]);

        $order->payment()->create([
            'payment_method' => 'CASH',
            'payment_status' => 'PENDING',
        ]);
    }
}