<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Player extends Authenticatable
{
    use HasFactory;

    protected $table = 'players';

    protected $fillable = ['player_username', 'password','room_id'];

    protected $hidden = ['password', 'remember_token'];

    public function room(){
        return $this->belongsTo(Room::class);
    }

    public function pinjaman(){
        return $this->belongsTo(Pinjaman::class);
    }

    public function demand(){
        return $this->hasMany(Demand::class);
    }
}
