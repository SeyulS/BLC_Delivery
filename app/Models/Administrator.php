<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Administrator extends Authenticatable
{
    protected $table = 'administrators';

    protected $fillable = ['admin_username', 'password'];

    protected $hidden = ['password', 'remember_token'];

    public function room(){
        $this->hasMany(Room::class);
    }
}
