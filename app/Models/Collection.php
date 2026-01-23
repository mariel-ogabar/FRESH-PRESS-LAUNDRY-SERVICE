<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model {
    const STATUS_RECEIVED = 'RECEIVED';
    const STATUS_PENDING = 'PENDING';

    protected $fillable = ['order_id', 'collection_method', 'collection_status', 'collection_date'];
    protected $casts = [
        'collection_date' => 'datetime',
    ];

    public function order() { return $this->belongsTo(Order::class); }
}