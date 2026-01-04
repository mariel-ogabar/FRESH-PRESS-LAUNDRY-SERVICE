<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AddOn extends Model {
    protected $fillable = ['addon_name', 'addon_price', 'multiple_allowed', 'is_active'];
}