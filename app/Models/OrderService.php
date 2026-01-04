<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderService extends Model {
    protected $fillable = ['order_id', 'service_id', 'quantity', 'service_price'];

    public function order() { return $this->belongsTo(Order::class); }
    public function mainService() { return $this->belongsTo(MainService::class, 'service_id'); }
    public function addons() { return $this->hasMany(OrderAddon::class); }
}