<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Order extends Model {
    use SoftDeletes;

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_CANCELLED = 'CANCELLED';
    const STATUS_COMPLETED = 'COMPLETED';
   
    protected $fillable = ['user_id', 'total_price', 'order_status'];

    /**
     * GLOBAL SECURITY SCOPE
     * This automatically filters all queries for Customers.
     */
    protected static function booted()
    {
        static::addGlobalScope('own_orders', function (Builder $builder) {
            if (Auth::check()) {
                $user = Auth::user();
                if (!$user->hasAnyRole(['ADMIN', 'STAFF'])) {
                    $builder->where('orders.user_id', $user->id);
                }
            }
        });
    }

    public function user() { return $this->belongsTo(User::class); }
    public function services() { return $this->hasMany(OrderService::class); }
    public function payment() { return $this->hasOne(Payment::class); }
    public function laundryStatus() { return $this->hasOne(LaundryStatus::class); }
    public function collection() { return $this->hasOne(Collection::class); }
    public function delivery() { return $this->hasOne(Delivery::class); }
    public function audits() { return $this->hasMany(LaundryStatusAudit::class)->orderBy('changed_at', 'desc'); }

    
    public function isReceived(): bool
    {
        return $this->collection && $this->collection->collection_status === 'RECEIVED';
    }

    public function isCancellable(): bool
    {
        return $this->order_status === self::STATUS_ACTIVE && 
               $this->collection->collection_status === 'PENDING' && 
               $this->payment->payment_status === 'PENDING';
    }

}
