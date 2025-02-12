<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    public $table = 'machine';
    use HasFactory;

    public function item()
    {
        return $this->belongsTo(Items::class, 'machine_item_index', 'id');
    }
}
