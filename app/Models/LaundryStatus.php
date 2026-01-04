<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LaundryStatus extends Model {
    protected $fillable = ['order_id', 'current_status'];
    public function order() { return $this->belongsTo(Order::class); }
}