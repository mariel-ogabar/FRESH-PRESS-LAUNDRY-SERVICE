<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_id';

    // These are the columns allowed to be saved
    protected $fillable = [
        'user_id',
        'total_price',
        'order_status',
    ];

    // Relationships
    public function services()
    {
        return $this->hasMany(OrderService::class, 'order_id', 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}