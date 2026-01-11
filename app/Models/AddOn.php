<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddOn extends Model {

    use SoftDeletes;
    
    protected $fillable = ['addon_name', 'addon_price', 'multiple_allowed', 'is_active'];
}