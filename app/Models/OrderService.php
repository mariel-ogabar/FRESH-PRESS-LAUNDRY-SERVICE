<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrderService extends Model
{
    protected $primaryKey = 'order_service_id';
    protected $fillable = ['order_id', 'service_id', 'quantity', 'service_price'];

    public function mainService() {
        return $this->belongsTo(MainService::class, 'service_id');
    }
}