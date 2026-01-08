<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $primaryKey = 'user_id';
    
    protected $fillable = [
        'name', 'email_add', 'password', 'role', 'contact_no', 'address'
    ];

    public function getAuthPassword() {
        return $this->password;
    }
}