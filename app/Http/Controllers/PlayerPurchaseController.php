<?php

namespace App\Http\Controllers;

use App\Events\UpdateRevenue;
use App\Events\UpdateWarehouse;
use App\Models\Items;
use App\Models\Machine;
use App\Models\Player;
use App\Models\RevenueHistory;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlayerPurchaseController extends Controller
{
    public function purchaseWarehouse(Request $request)
    {
        $room = Room::where('room_id', $request->input('room_id'))->first();
        $player = Player::where('player_username', Auth::guard('player')->user()->player_username)->first();
        $prevRevenue = $player->revenue;
        if ($room->status == 0){
            return response()->json([
                'success' => false,
                'message' => 'The simulation was paused',
            ]);
        }
        if ($player->revenue - ($request->input('quantityPurchase') * $room->warehouse_price) < 0) {
            return response()->json([
                'success' => false,
                'message' => 'Pembelian Warehouse Gagal, Saldo Tidak Mencukupi',
            ]);
        }

        $player->revenue = $player->revenue - ($request->input('quantityPurchase') * $room->warehouse_price);
        $player->inventory = $player->inventory + ($request->input('quantityPurchase')*$room->warehouse_size);

        $totalCapacity = 0;

        $currentMachineCapacity = json_decode($player->machine_capacity);
        $currentItemCapacity = json_decode($player->items);
        $roomMachineIndex = json_decode($room->machine_chosen);
        $roomItemIndex = json_decode($room->item_chosen);

        for ($i = 0; $i < count($roomMachineIndex); $i++) {
            $queryMachine = Machine::where('id', $roomMachineIndex[$i])->first();
            $queryItem = Items::where('id', $roomItemIndex[$i])->first();
            $totalCapacity = $totalCapacity + ($currentMachineCapacity[$i] * $queryMachine->machine_size) + ($currentItemCapacity[$i] * $queryItem->item_size);
        }

        $revenueHistory = new RevenueHistory();
        $revenueHistory->room_id = $room->room_id;
        $revenueHistory->day = $room->recent_day;
        $revenueHistory->player_username = $player->player_username;
        $revenueHistory->transaction_description = 'Warehouse Purchase';
        $revenueHistory->revenue_before = $prevRevenue;
        $revenueHistory->revenue_after = $player->revenue;
        $revenueHistory->value = $request->input('quantityPurchase') * $room->warehouse_price * -1;
        $revenueHistory->save();

        UpdateRevenue::dispatch($player->player_username, $room->room_id);

        $player->save();
        return response()->json([
            'success' => true,
            'player_revenue' => $player->revenue,
            'player_inventory' => $player->inventory,
            'currentWarehouse' => $player->inventory,
            'currentCapacity' => $totalCapacity,
            'quantity' => $request->input('quantityPurchase'),
            'message' => 'Warehouse Purchase Success',
        ]);
    }

    public function purchaseMachine(Request $request)
    {
        $room = Room::where('room_id', $request->input('room_id'))->first();
        if($room->status == 0){
            return response()->json([
                'status' => 'fail',
                'message' => 'The simulation was paused'
            ]);
        }
        $machine = Machine::where('id', $request->input('machineType'))->first();
        $player = Player::where('player_username', Auth::guard('player')->user()->player_username)->first();

        $prevRevenue = $player->revenue;
        $price = $machine->machine_price;
        $capacity = $machine->machine_size;

        if ($player->revenue - $price < 0) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Dont have enough money !!'
            ]);
        }

        $totalCapacity = 0;

        $currentMachineCapacity = json_decode($player->machine_capacity);
        $currentItemCapacity = json_decode($player->items);
        $roomMachineIndex = json_decode($room->machine_chosen);
        $roomItemIndex = json_decode($room->item_chosen);

        for ($i = 0; $i < count($roomMachineIndex); $i++) {
            $queryMachine = Machine::where('id', $roomMachineIndex[$i])->first();
            $queryItem = Items::where('id', $roomItemIndex[$i])->first();
            $totalCapacity = $totalCapacity + ($currentMachineCapacity[$i] * $queryMachine->machine_size) + ($currentItemCapacity[$i] * $queryItem->item_length * $queryItem->item_width);
        }


        if ($totalCapacity + $capacity > $player->inventory) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Warehouse doesnt fit !!'
            ]);
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
        $currentProductionCapacity = [];
        $machineName = [];

        for ($i = 0; $i < count($roomMachineIndex); $i++) {
            $machineName[] = Machine::where('id', $roomMachineIndex[$i])->first()->machine_name;
            $currentProductionCapacity[] = $currentMachineCapacity[$i] * Machine::where('id', $roomMachineIndex[$i])->first()->production_capacity;
        }

        $player->machine_capacity = json_encode($currentMachineCapacity);
        $player->save();

        $revenueHistory = new RevenueHistory();
        $revenueHistory->room_id = $room->room_id;
        $revenueHistory->day = $room->recent_day;
        $revenueHistory->player_username = $player->player_username;
        $revenueHistory->transaction_description = '['. $machine->machine_name . ']' . '    Purchase';
        $revenueHistory->revenue_before = $prevRevenue;
        $revenueHistory->revenue_after = $player->revenue;
        $revenueHistory->value = $price * -1;
        $revenueHistory->save();

        UpdateRevenue::dispatch($player->player_username, $room->room_id);

        return response()->json([
            'status' => 'success',
            'message' => 'Machine Purchase Success !!',
            'machineName' => $machineName,
            'machineQuantity' => $currentMachineCapacity,
            'currentCapacity' => $currentProductionCapacity,
            'revenue' => $player->revenue
        ]);
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
            $queryItem = Items::where('id', $roomItemIndex[$i])->first();
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
