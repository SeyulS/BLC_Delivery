<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
protected $table = 'room';

    protected $fillable = ['room_id'];
    
    protected  $primaryKey = 'room_id';

    public function player(){
        $this->hasMany(Player::class);
    }

    
}
