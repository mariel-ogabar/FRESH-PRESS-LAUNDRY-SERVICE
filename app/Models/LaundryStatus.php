<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LaundryStatus extends Model {

    const PENDING = 'PENDING';
    const WASHING = 'WASHING';
    const DRYING = 'DRYING';
    const FOLDING = 'FOLDING';
    const IRONING = 'IRONING';
    const READY = 'READY';
    
    protected $fillable = ['order_id', 'current_status'];
    public function order() { return $this->belongsTo(Order::class); }
}