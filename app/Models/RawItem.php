<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawItem extends Model
{
    use HasFactory;

    protected $table = 'raw_items';

    // Menambahkan kolom yang dapat diisi secara massal
    protected $fillable = [
        'raw_item_name',
        'raw_item_price',
    ];
}
