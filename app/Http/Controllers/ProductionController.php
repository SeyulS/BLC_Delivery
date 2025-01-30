<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\Machine;
use App\Models\Player;
use App\Models\Raw_Item;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductionController extends Controller
{
    public function produce(Request $request)
    {
        $player = Player::where('player_username', Auth::guard('player')->user()->player_username)->first();

        $room = Room::where('room_id', $player->room_id)->first();

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

            // Cari harga material
            $price = 0;
            for ($i = 0; $i < count($rawItem); $i++) {
                $query = Raw_Item::where('id', $rawItem[$i])->first();
                $price = $price + ($query->raw_item_price * $BOM[$i]);
            }

            $playerRawItems = json_decode($player->raw_items);
            $playerItems = json_decode($player->items);

            // Cek Apakah Raw Item Player Cukup ???
            for ($i = 0; $i < count($playerRawItems); $i++) {
                if ($playerRawItems[$i] < $BOM[$i]) {
                    return back()->with('fail', 'Bahan Tidak Mencukupi');
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
                return back()->with('fail', 'Warehouse Tidak Mencukupi');
            }

            // Cek Saldo Player
            if ($player->revenue - $price < 0) {
                return back()->with('fail', 'Saldo Tidak Mencukupi');
            }


            for ($i = 0; $i < count($playerRawItems); $i++) {
                $playerRawItems[$i] = $playerRawItems[$i] - $BOM[$i];
            }

            for ($i = 0; $i < count($playerItems); $i++) {
                $playerItems[$i] = $playerItems[$i] + $inputProduce[$i];
            }


            $player->raw_items = json_encode($playerRawItems);
            $player->revenue = $player->revenue - $price;
            $player->items = json_encode($playerItems);
            $player->produce = 0;
            $player->save();


            return back()->with('success', 'Berhasil Diproduksi !');
        } else {
            return back()->with('fail', 'Hanya bisa memproduksi 1 kali !');
        }
    }
}
