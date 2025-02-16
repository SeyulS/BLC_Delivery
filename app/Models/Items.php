<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    use HasFactory;
    protected $table = 'items';

    public function getBOMAttribute()
    {
        $bom = [];

        $rawItemIds = json_decode($this->raw_item_needed, true); // Mengonversi dari JSON ke array
        $quantities = json_decode($this->raw_quantity_needed, true); // Mengonversi dari JSON ke array

        // Periksa jika data tidak null atau kosong
        if (is_array($rawItemIds) && is_array($quantities)) {
            foreach ($rawItemIds as $index => $rawItemId) {
                $rawItem = RawItem::find($rawItemId); // Cari data raw item berdasarkan ID

                if ($rawItem) {
                    $bom[] = $quantities[$index] . 'x ' . $rawItem->raw_item_name;
                }
            }
        }

        return implode(', ', $bom); // Menggabungkan semua item menjadi string
    }

    public function raw_items()
    {
        return $this->hasMany(RawItem::class, 'id', 'raw_item_id');
    }


}
