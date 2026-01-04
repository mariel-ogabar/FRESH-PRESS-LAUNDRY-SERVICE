<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model {
    protected $fillable = ['order_id', 'collection_method', 'collection_status', 'collection_date'];
    public function order() { return $this->belongsTo(Order::class); }
}