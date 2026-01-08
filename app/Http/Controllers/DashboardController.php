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

        $query = Order::with([
                'user:id,name,email,contact_no,address', 
                'laundryStatus',
                'collection',
                'payment',
                'delivery',
                'services.mainService',
                'audits' => fn($q) => $q->latest('changed_at') 
            ])
            ->withCount('services'); 

        if ($user->role === 'CUSTOMER') {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('status')) {
            if ($request->status === \App\Models\Order::STATUS_CANCELLED) {
                $query->onlyTrashed();
            } else {
                $query->where('order_status', $request->status);
            }
        }

        if ($request->filled('service')) {
            $query->whereHas('services', fn($q) => $q->where('service_id', $request->service));
        }

        if ($request->filled('payment')) {
            $query->whereHas('payment', fn($q) => $q->where('payment_status', $request->payment));
        }

        $mainServices = MainService::all();

        $view = in_array($user->role, ['ADMIN', 'STAFF']) ? 'dashboard.admin_staff' : 'dashboard.customer';

        $orders = $query->latest()->paginate(10)->withQueryString(); 
        
        return view($view, compact('orders', 'mainServices'));
    }
}