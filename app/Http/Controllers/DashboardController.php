<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\MainService;
use App\Models\LaundryStatus;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the filtered dashboard with global statistics.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Base Query for the TABLE 
        $tableQuery = Order::withTrashed()->with([
            'user:id,name,email,contact_no,address',
            'laundryStatus',
            'collection',
            'payment',
            'delivery',
            'services.mainService',
            'audits'
        ]);

        // 2. Apply Filters to the Table Query
        if ($request->filled('status')) {
            $tableQuery->where('order_status', $request->status);
        }

        if ($request->filled('service')) {
            $tableQuery->whereHas('services', fn($q) => $q->where('service_id', $request->service));
        }

        if ($request->filled('payment_status')) {
            $tableQuery->whereHas('payment', fn($q) => $q->where('payment_status', $request->payment_status));
        }

        // 3. Logic for COUNTERS (Strictly exclude Cancelled/Deleted orders)
        $statsBase = Order::where('order_status', '!=', 'CANCELLED');

        $stats = [
            ['label' => 'Total Orders', 'value' => (clone $statsBase)->count()],
            
            ['label' => 'Pending', 'value' => (clone $statsBase)->whereHas('laundryStatus', 
                fn($q) => $q->where('current_status', 'PENDING'))->count()],
            
            ['label' => 'In Progress', 'value' => (clone $statsBase)->whereHas('laundryStatus', 
                fn($q) => $q->whereIn('current_status', ['WASHING', 'DRYING', 'FOLDING', 'IRONING']))->count()],
            
            ['label' => 'Ready', 'value' => (clone $statsBase)->whereHas('laundryStatus', 
                fn($q) => $q->where('current_status', 'READY'))->count()],
            
            ['label' => 'Paid Sales', 'value' => number_format((clone $statsBase)->whereHas('payment', 
                fn($q) => $q->where('payment_status', Payment::STATUS_PAID))->sum('total_price'), 2)],
            
            ['label' => 'To be Paid', 'value' => number_format((clone $statsBase)->whereHas('payment', 
                fn($q) => $q->where('payment_status', Payment::STATUS_PENDING))->sum('total_price'), 2)],
        ];

        // 4. Final Data Fetch with Pagination 
        $mainServices = MainService::all(); 
        $orders = $tableQuery->latest()->paginate(20)->withQueryString();

        // 5. Route to appropriate view based on Role
        $view = $user->hasAnyRole(['ADMIN', 'STAFF']) ? 'dashboard.admin_staff' : 'dashboard.customer';
    
        return view($view, compact('orders', 'mainServices', 'stats'));
    }
}