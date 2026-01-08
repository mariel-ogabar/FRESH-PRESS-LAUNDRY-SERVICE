<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MainService extends Model
{
    protected $primaryKey = 'service_id';
    protected $fillable = ['service_name', 'pricing_type', 'service_base_price', 'is_active'];
}