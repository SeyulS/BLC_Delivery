<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\Player;
use App\Models\Room;
use App\Models\Raw_item;
use Illuminate\Http\Request;
use Termwind\Components\Raw;

class SettingBahanBaku extends Controller
{
    public function index($room_id){
        $room = Room::where('room_id', $room_id)->first();
        $itemChosen = json_decode($room->item_chosen, true);    

        $rawItem = [];
        foreach ($itemChosen as $item) {
            $i = Items::where('item_id', $item)->first();
            if ($i) {
                $rawItem[] = $i->raw_item_needed;
            }
        }
        return view('Admin.fitur.bahan_baku',[
            'room_id' => $room_id,
            'players' => Player::where('room_id', $room_id)->get(),
            'rawItems' => $rawItem
        ]);
    }
}
