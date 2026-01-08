<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LaundryStatus extends Model
{
    protected $primaryKey = 'laundry_status_id'; 
    protected $fillable = ['order_id', 'current_status'];
}