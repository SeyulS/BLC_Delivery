<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demand extends Model
{
    use HasFactory;
    protected $table = 'demand';

    public function player()
    {
        $this->belongsTo(Player::class);
    }

    // Model Demand.php
    public function item()
    {
        return $this->belongsTo(Items::class, 'item_index', 'id');
    }
}
