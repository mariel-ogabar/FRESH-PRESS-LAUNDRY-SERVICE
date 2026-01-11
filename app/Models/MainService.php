<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MainService extends Model {

    use SoftDeletes;
    protected $fillable = ['service_name', 'pricing_type', 'service_base_price', 'is_active'];
}