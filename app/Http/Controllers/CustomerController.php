<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderService;
use App\Models\MainService;

class CustomerController extends Controller
{
    // 1. Show the Dashboard with Order History
    public function index(Request $request)
    {
        // Get sorting parameters
        $sortBy = $request->get('sort', 'created_at'); // default: newest first
        $sortDirection = $request->get('direction', 'desc'); // default: descending

        // Validate sort parameters for security
        $allowedSorts = ['created_at', 'total_price', 'order_status'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        // Get orders for the logged-in user with sorting
        $orders = Order::where('user_id', Auth::id())
                    ->with('services.mainService') // Load service details
                    ->orderBy($sortBy, $sortDirection)
                    ->get();

        return view('customer.dashboard', compact('orders', 'sortBy', 'sortDirection'));
    }

    // 2. Show the Booking Form
    public function create()
    {
        // Get all active services for the dropdown
        $services = MainService::where('is_active', true)->get();
        
        return view('customer.book', compact('services'));
    }

    // 3. Store the New Order (The "Brain" Logic)
    public function store(Request $request)
    {
        // A. Validate Input (Enforcing the 15kg Limit)
        $request->validate([
            'service_id' => 'required|exists:main_services,service_id',
            'quantity' => 'required|numeric|min:1|max:15', // Stops anything over 15
        ]);

        // B. Get Service Details to Calculate Price
        $service = MainService::findOrFail($request->service_id);
        $total_price = $service->service_base_price * $request->quantity;

        // C. Create the Order
        // We set status to 'ACTIVE' so you can see the Cancel button immediately
        $order = Order::create([
            'user_id' => Auth::id(),
            'order_status' => 'ACTIVE', 
            'total_price' => $total_price,
        ]);

        // D. Save the Item Details
        OrderService::create([
            'order_id' => $order->order_id,
            'service_id' => $service->service_id,
            'quantity' => $request->quantity,
            'service_price' => $service->service_base_price,
        ]);

        // E. Redirect back to Dashboard
        return redirect()->route('dashboard')->with('success', 'Order placed successfully!');
    }

    // 4. Edit an Order
    public function edit($id)
    {
        // Find the order (Ensure it belongs to the logged-in user)
        $order = Order::where('order_id', $id)
                      ->where('user_id', Auth::id())
                      ->with('services.mainService')
                      ->firstOrFail();

        // Only allow editing if it's ACTIVE
        if ($order->order_status !== 'ACTIVE') {
            return redirect()->route('dashboard')->with('error', 'Only active orders can be edited.');
        }

        // Get the first service (assuming one service per order for simplicity)
        $orderService = $order->services->first();
        $services = MainService::where('is_active', true)->get();

        return view('customer.edit-order', compact('order', 'orderService', 'services'));
    }

    // 5. Update an Order
    public function update(Request $request, $id)
    {
        $request->validate([
            'service_id' => 'required|exists:main_services,service_id',
            'quantity' => 'required|numeric|min:1|max:15',
        ]);

        // Find the order
        $order = Order::where('order_id', $id)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();

        // Only allow updating if it's ACTIVE
        if ($order->order_status !== 'ACTIVE') {
            return redirect()->route('dashboard')->with('error', 'Only active orders can be updated.');
        }

        DB::transaction(function () use ($request, $order) {
            $service = MainService::findOrFail($request->service_id);
            $total_price = $service->service_base_price * $request->quantity;

            // Update the order
            $order->update(['total_price' => $total_price]);

            // Update or create the order service
            $order->services()->delete(); // Remove existing services
            OrderService::create([
                'order_id' => $order->order_id,
                'service_id' => $service->service_id,
                'quantity' => $request->quantity,
                'service_price' => $total_price,
            ]);
        });

        return redirect()->route('dashboard')->with('success', 'Order updated successfully!');
    }

    // 6. Cancel an Order
    public function cancel($id)
    {
        // Find the order (Ensure it belongs to the logged-in user)
        $order = Order::where('order_id', $id)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();

        // Only allow cancellation if it's currently Active or Pending
        if ($order->order_status == 'ACTIVE' || $order->order_status == 'PENDING') {
            $order->update(['order_status' => 'CANCELLED']);
            return redirect()->back()->with('success', 'Order cancelled successfully.');
        }

        return redirect()->back()->with('error', 'This order cannot be cancelled.');
    }
}