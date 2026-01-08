<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order; 
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // 1. Show the Booking Page
    public function create()
    {
        return view('orders.create');
    }

    // 2. Save the Order
    public function store(Request $request)
    {
        $request->validate([
            'weight' => 'required|numeric|min:0.1', 
        ]);

        $weight = $request->input('weight');
        $totalPrice = $weight * 10; // $10 per kilo

        Order::create([
            'user_id' => Auth::id(),        
            'service_details' => 'Washing & Folding',
            'weight' => $weight,            
            'price' => $totalPrice,         
            'status' => 'Pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Order placed successfully!');
    }
}