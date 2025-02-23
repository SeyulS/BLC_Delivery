<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\Machine;
use App\Models\Player;
use App\Models\ProductionHistory;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductionController extends Controller
{
    public function produce(Request $request)
    {
        $player = Player::where('player_username', Auth::guard('player')->user()->player_username)->first();

        $room = Room::where('room_id', $player->room_id)->first();
        if ($room->status == 0){
            return back()->with('fail', 'The simulation was paused !');
        }
        if ($player->produce == 1) {
            $machineList = $request->input('machine_id', []);
            $inputProduce = $request->input('quantityProduce', []);

            $inputProduce = array_map(function ($quantity) {
                return $quantity === null || $quantity === '' ? 0 : (int)$quantity;
            }, $inputProduce);

            // Item apa saja yang dibuat
            $itemList = [];
            for ($i = 0; $i < count($machineList); $i++) {
                $query = Machine::where('id', $machineList[$i])->first();
                $itemList[] = $query->machine_item_index;
            }

            $rawItem = [];

            foreach ($itemList as $item) {
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
            $rawItem = array_values($rawItem);
            $BOM = array_fill(0, count($rawItem), 0); // Ini buat Array 0 0 0 sepanjang rawitem

            for ($i = 0; $i < count($itemList); $i++) {
                $query = Items::where('id', $itemList[$i])->first();
                $rawItemIndex = json_decode($query->raw_item_needed);
                $rawQuantityNeeded = json_decode($query->raw_quantity_needed);

                for ($j = 0; $j < count($rawItemIndex); $j++) {

                    $index = array_search($rawItemIndex[$j], $rawItem);
                    if ($index !== false) {
                        $BOM[$index] += $inputProduce[$i] * $rawQuantityNeeded[$j];
                    }
                }
            }

            $playerRawItems = json_decode($player->raw_items);
            $playerItems = json_decode($player->items);

            // Cek Apakah Raw Item Player Cukup ???
            for ($i = 0; $i < count($playerRawItems); $i++) {
                if ($playerRawItems[$i] < $BOM[$i]) {
                    return back()->with('fail', 'You need more raw items !');
                }
            }

            $sizeItemProduce = 0;
            for ($i = 0; $i < count($itemList); $i++) {
                $query = Items::where('id', $itemList[$i])->first();
                $sizeItemProduce = $sizeItemProduce + ($query->item_width * $query->item_length * $inputProduce[$i]);
            }

            $currentCapacity = 0;

            $currentMachineCapacity = json_decode($player->machine_capacity);
            $currentItemCapacity = json_decode($player->items);
            $roomMachineIndex = json_decode($room->machine_chosen);
            $roomItemIndex = json_decode($room->item_chosen);

            for ($i = 0; $i < count($roomMachineIndex); $i++) {
                $queryMachine = Machine::where('id', $roomMachineIndex[$i])->first();
                $queryItem = Items::where('id', $roomItemIndex[$i])->first();
                $currentCapacity = $currentCapacity + ($currentMachineCapacity[$i] * $queryMachine->machine_size) + ($currentItemCapacity[$i] * $queryItem->item_length * $queryItem->item_width);
            }

            // Cek Inventory Cukup ?
            if ($currentCapacity + $sizeItemProduce > $player->inventory) {
                return back()->with('fail', 'You need larger warehouse !');
            }

            for ($i = 0; $i < count($playerRawItems); $i++) {
                $playerRawItems[$i] = $playerRawItems[$i] - $BOM[$i];
            }

            for ($i = 0; $i < count($playerItems); $i++) {
                $playerItems[$i] = $playerItems[$i] + $inputProduce[$i];
            }


            $player->raw_items = json_encode($playerRawItems);
            $player->items = json_encode($playerItems);
            $player->produce = 0;
            $player->save();

            $history = new ProductionHistory();
            $history->room_id = $room->room_id;
            $history->day = $room->recent_day;
            $history->player_username = $player->player_username;
            $history->raw_item_spended = json_encode($BOM);
            $history->production_items = json_encode($inputProduce);
            $history->save();


            return back()->with('success', 'Successfully Produce !');
        } else {
            return back()->with('fail', 'You can only produce once !');
        }
    }
}
