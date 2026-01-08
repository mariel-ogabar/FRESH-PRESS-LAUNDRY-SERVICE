<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model {

    const STATUS_PENDING = 'PENDING';
    const STATUS_PAID = 'PAID';
    
    protected $fillable = ['order_id', 'payment_method', 'payment_status', 'payment_date'];
    protected $casts = [
        'payment_date' => 'datetime',
    ];

    public function order() { return $this->belongsTo(Order::class); }
}