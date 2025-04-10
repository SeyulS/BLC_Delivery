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

        $rawItemIds = json_decode($this->raw_item_needed, true); // Convert JSON to array
        $quantities = json_decode($this->raw_quantity_needed, true); // Convert JSON to array

        // Check if data is not null or empty
        if (is_array($rawItemIds) && is_array($quantities)) {
            foreach ($rawItemIds as $index => $rawItemId) {
                $rawItem = RawItem::find($rawItemId); // Find raw item by ID

                if ($rawItem) {
                    $bom[] = $quantities[$index] . 'x ' . $rawItem->raw_item_name;
                }
            }
        }

        return $bom; // Return as an array
    }

    public function raw_items()
    {
        return $this->hasMany(RawItem::class, 'id', 'raw_item_id');
    }
}
