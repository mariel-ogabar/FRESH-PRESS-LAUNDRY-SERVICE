<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    protected $fillable = ['user_id', 'total_price', 'order_status'];

    public function user() { return $this->belongsTo(User::class); }
    public function services() { return $this->hasMany(OrderService::class); }
    public function payment() { return $this->hasOne(Payment::class); }
    public function laundryStatus() { return $this->hasOne(LaundryStatus::class); }
    public function collection() { return $this->hasOne(Collection::class); }
    public function delivery() { return $this->hasOne(Delivery::class); }
    public function audits() { return $this->hasMany(LaundryStatusAudit::class); }
}