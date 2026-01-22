<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model {
    
    const STATUS_PENDING = 'PENDING';
    const STATUS_READY = 'READY';
    const STATUS_DELIVERED = 'DELIVERED';
    
    protected $fillable = [
        'order_id',
        'delivery_method',
        'delivery_status',
        'scheduled_delivery_date', 
        'delivered_date',           
    ];

    protected $casts = [
        'scheduled_delivery_date' => 'datetime',
        'delivered_date' => 'datetime',
    ];

    public function order() { return $this->belongsTo(Order::class); }
} 