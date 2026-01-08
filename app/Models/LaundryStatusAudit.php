<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaundryStatusAudit extends Model {
    public $timestamps = false; 

    protected $fillable = [
        'order_id', 
        'old_status', 
        'new_status', 
        'changed_at'
    ]; 

    public function order() { 
        return $this->belongsTo(Order::class); 
    }
}