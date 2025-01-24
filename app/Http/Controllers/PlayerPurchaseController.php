<?php

namespace App\Http\Controllers;

use App\Events\UpdateRevenue;
use App\Events\UpdateWarehouse;
use App\Models\Items;
use App\Models\Machine;
use App\Models\Player;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlayerPurchaseController extends Controller
{
    public function purchaseWarehouse(Request $request)
    {
        $room = Room::where('room_id', $request->input('room_id'))->first();
        $player = Player::where('player_username', Auth::guard('player')->user()->player_username)->first();


        $price = $room->warehouse_price;

        if ($player->revenue - $price < 0) {
            // Jika gagal
        return response()->json([
            'success' => false,
            'message' => 'Pembelian Warehouse Gagal, Saldo Tidak Mencukupi',
        ]);
        }

        $player->inventory = $player->inventory + $room->warehouse_size;
        $player->revenue = $player->revenue - $room->warehouse_price;

        UpdateRevenue::dispatch();
        UpdateWarehouse::dispatch();

        $player->save();
        return response()->json([
            'success' => true,
            'message' => 'Pembelian Warehouse Berhasil',
        ]);
    
    }

    public function purchaseMachine(Request $request)
    {
        $room = Room::where('room_id', $request->input('room_id'))->first();
        $machine = Machine::where('id', $request->input('machineType'))->first();
        $player = Player::where('player_username', Auth::guard('player')->user()->player_username)->first();


        $price = $machine->machine_price;
        $capacity = $machine->machine_size;

        if ($player->revenue - $price < 0) {
            return back()->with('error', 'Saldo tidak mencukupi');
        }

        $totalCapacity = 0;

        $currentMachineCapacity = json_decode($player->machine_capacity);
        $currentItemCapacity = json_decode($player->items);
        $roomMachineIndex = json_decode($room->machine_chosen);
        $roomItemIndex = json_decode($room->item_chosen);

        for ($i = 0; $i < count($roomMachineIndex); $i++) {
            $queryMachine = Machine::where('id', $roomMachineIndex[$i])->first();
            $queryItem = Items::where('id',$roomItemIndex[$i])->first();
            $totalCapacity = $totalCapacity + ($currentMachineCapacity[$i] * $queryMachine->machine_size) + ($currentItemCapacity[$i] * $queryItem->item_size);
        }


        if ($totalCapacity + $capacity > $player->inventory) {
            return back()->with('error', 'Warehouse tidak cukup !');
        }


        $player->revenue = $player->revenue - $price;

        $index = 0;
        for ($i = 0; $i < count($roomMachineIndex); $i++) {
            if ($roomMachineIndex[$i] != $request->input('machineType')) {
                $index = $index + 1;
            } else {
                break;
            }
        }

        $currentMachineCapacity[$index] = $currentMachineCapacity[$index] + 1;
        $player->machine_capacity = json_encode($currentMachineCapacity);
        $player->save();

        return back()->with('success', 'Pembelian Mesin Berhasil');
    }

    public function updateWarehouse(Request $request)
    {
        $player = Player::where('player_username', $request->player_id)->first();
        $room = Room::where('room_id', $request->room_id)->first();
        $totalCapacity = 0;

        $currentMachineCapacity = json_decode($player->machine_capacity);
        $currentItemCapacity = json_decode($player->items);
        $roomMachineIndex = json_decode($room->machine_chosen);
        $roomItemIndex = json_decode($room->item_chosen);

        for ($i = 0; $i < count($roomMachineIndex); $i++) {
            $queryMachine = Machine::where('id', $roomMachineIndex[$i])->first();
            $queryItem = Items::where('id',$roomItemIndex[$i])->first();
            $totalCapacity = $totalCapacity + ($currentMachineCapacity[$i] * $queryMachine->machine_size) + ($currentItemCapacity[$i] * $queryItem->item_size);
        }

        if ($player) {
            return response()->json([
                'warehouseCapacity' => $player->inventory,
                'usedCapacity' => $totalCapacity
            ]);
        }

        return response()->json(['error' => 'Player not found'], 404);
    }
}
