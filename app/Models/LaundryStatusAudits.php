<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LaundryStatusAudit extends Model {
    public $timestamps = false; // Audits use changed_at, not Laravel timestamps
    protected $guarded = [];
    public function order() { return $this->belongsTo(Order::class); }
}