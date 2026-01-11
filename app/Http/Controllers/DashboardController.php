<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\MainService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Initial Query with Eager Loading (Global scope handles privacy)
        $query = Order::with([
                'user:id,name,email',
                'laundryStatus',
                'collection',
                'payment',
                'delivery',
                'services.mainService',
                'audits'
            ]);

        // 2. Apply Filters (Admin/Staff only)
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('service')) {
            $query->whereHas('services', fn($q) => $q->where('service_id', $request->service));
        }

        // 3. FETCH THE MISSING VARIABLE
        $mainServices = MainService::all(); // This is what the error is complaining about

        $orders = $query->latest()->paginate(10)->withQueryString();

        // 4. Dynamic View Selection
        $view = $user->hasAnyRole(['ADMIN', 'STAFF']) ? 'dashboard.admin_staff' : 'dashboard.customer';
    
        // 5. THE FIX: Ensure $mainServices is in the compact list
        return view($view, compact('orders', 'mainServices'));
    }
}