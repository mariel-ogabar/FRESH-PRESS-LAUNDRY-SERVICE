<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model {
    
    use SoftDeletes;

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_CANCELLED = 'CANCELLED';
    const STATUS_COMPLETED = 'COMPLETED';
    
    protected $fillable = ['user_id', 'total_price', 'order_status'];

    public function user() { return $this->belongsTo(User::class); }
    public function services() { return $this->hasMany(OrderService::class); }
    public function payment() { return $this->hasOne(Payment::class); }
    public function laundryStatus() { return $this->hasOne(LaundryStatus::class); }
    public function collection() { return $this->hasOne(Collection::class); }
    public function delivery() { return $this->hasOne(Delivery::class); }
    public function audits() { return $this->hasMany(LaundryStatusAudit::class)->orderBy('changed_at', 'desc'); }
    public function addons()
    {
        return $this->belongsToMany(
            AddOn::class,    
            'order_addon',  
            'order_service_id',      
            'addon_id'       
        );
    }

    public function isCancellable(): bool
    {
        $isCollectionPending = $this->collection->collection_status === \App\Models\Collection::STATUS_PENDING;
        $isPaymentPending = $this->payment->payment_status === \App\Models\Payment::STATUS_PENDING;

        return $this->order_status === self::STATUS_ACTIVE && $isCollectionPending && $isPaymentPending;
    }
}