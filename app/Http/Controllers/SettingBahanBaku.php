<?php

namespace App\Http\Controllers;

use App\Events\UpdateRevenue;
use App\Models\Items;
use App\Models\Player;
use App\Models\Room;
use App\Models\Raw_item;
use Illuminate\Http\Request;
use Termwind\Components\Raw;

class SettingBahanBaku extends Controller
{
    public function index($room_id)
    {
        $room = Room::where('room_id', $room_id)->first();
        $itemChosen = json_decode($room->item_chosen, true);

        $rawItem = [];

        foreach ($itemChosen as $item) {
            $i = Items::where('id', $item)->first();
            if ($i) {
                $rawItemsNeeded = is_string($i->raw_item_needed) ? json_decode($i->raw_item_needed, true) : $i->raw_item_needed;

                if (is_array($rawItemsNeeded)) {
                    foreach ($rawItemsNeeded as $raw) {
                        $rawItem[] = $raw;
                    }
                }
            }
        }
        $rawItem = array_unique($rawItem);
        $rawItemChosen = Raw_item::whereIn('id', $rawItem)->get();

        return view('Admin.fitur.bahan_baku', [
            'room' => $room,
            'players' => Player::where('room_id', $room_id)->get(),
            'rawItems' => $rawItemChosen
        ]);
    }


    public function setting(Request $request)
    {

        $price = 0;
        for ($i = 0; $i < count($request->items); $i++) {
            $query = Raw_item::where('id', $request->items[$i]['item_id'])->first();
            $price = $price + ($query->raw_item_price * $request->items[$i]['quantity']);
        }

        $query = Player::where('player_username', $request->player_id)->first();

        if ($query->revenue < $price) {
            return response()->json(['fail', "Saldo Player tidak mencukupi"]);
        } else {
            
            // Update Inventory Raw Items
            $rawItems = [];

            for ($i = 0; $i < count($request->items); $i++) {
                $rawItems[] = $request->items[$i]['quantity'];
            }

            $currentRawItems = json_decode($query->raw_items);
            for ($i = 0; $i < count($rawItems); $i++) {
                $currentRawItems[$i] = $currentRawItems[$i] + $rawItems[$i];
            }

            // Update
            $query->raw_items = json_encode($currentRawItems);
            
            // Potong saldo
            $query->revenue = $query->revenue - $price;
            $query->save();

            UpdateRevenue::dispatch();
            
            return response()->json("Pembelian Player Berhasil");
        }
    }
}
