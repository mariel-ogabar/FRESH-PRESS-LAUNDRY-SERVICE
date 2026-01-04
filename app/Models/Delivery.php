<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model {
    protected $fillable = ['order_id', 'delivery_method', 'delivery_status', 'delivery_date'];
    public function order() { return $this->belongsTo(Order::class); }
} 