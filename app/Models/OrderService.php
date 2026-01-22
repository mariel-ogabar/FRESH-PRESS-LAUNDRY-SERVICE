<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderService extends Model {
    protected $fillable = ['order_id', 'service_id', 'quantity', 'service_price'];

    public function order() { return $this->belongsTo(Order::class); }
    
    public function mainService() { 
        return $this->belongsTo(MainService::class, 'service_id'); 
    }

    /**
     * Many-to-Many relationship sa Add-ons
     */
    public function addons() { 
        return $this->belongsToMany(AddOn::class, 'order_addons', 'order_service_id', 'addon_id')
                    ->withPivot('addon_qty', 'addon_price')
                    ->withTimestamps(); 
    }
}