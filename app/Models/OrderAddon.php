<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAddon extends Model
{
    protected $guarded = [];

    public function orderService() { return $this->belongsTo(OrderService::class); }
    public function addonDefinition() { return $this->belongsTo(AddOn::class, 'addon_id'); }
}