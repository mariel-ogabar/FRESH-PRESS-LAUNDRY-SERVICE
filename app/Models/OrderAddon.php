<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAddon extends Model {
    protected $primaryKey = 'order_addon_id'; 
    protected $fillable = ['order_service_id', 'addon_id', 'addon_qty', 'addon_price'];

    public function orderService() { return $this->belongsTo(OrderService::class); }
    public function addonDefinition() { return $this->belongsTo(AddOn::class, 'addon_id'); }
}