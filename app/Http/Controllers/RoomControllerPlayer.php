<?php

namespace App\Http\Controllers;

use App\Events\PlayerJoin;
use App\Models\Items;
use App\Models\Machine;
use App\Models\Player;
use App\Models\Raw_item;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class RoomControllerPlayer extends Controller
{
    // Join Room
    public function index($roomCode)
    {
        $room = Room::where('room_id', $roomCode)->first();
        $player = Player::where('player_username', Auth::guard('player')->user()->player_username)->first();
        return view('Player.fitur.lobby', [
            'room' => $room,
            'player' => $player
            
        ]);
    }

    public function warehouseMachine($roomCode)
    {
        $room = Room::where('room_id', $roomCode)->first();
        $player = Player::where('player_username',Auth::guard('player')->user()->player_username)->first();
        $itemChosen = json_decode($room->item_chosen, true);

        $machineChosen = [];
        foreach ($itemChosen as $item) {
            $query = Machine::where('machine_item_index', $item)->first();
            $machineChosen[] = $query->id;
        }


        $machineChosenName = [];
        $machineCapacity = [];
        $machinePrice = [];

        foreach ($machineChosen as $machine) {
            $query = Machine::where('id', $machine)->first();
            $machineChosenName[] = $query->machine_name;
            $machineCapacity[] = $query->production_capacity;
            $machinePrice[] = $query->machine_price;
        }

        $playerMachineCapacity = [];
        $playerCapacity = json_decode(Auth::guard('player')->user()->machine_capacity);

        for ($i = 0; $i < count($machineCapacity);$i++){
            $playerMachineCapacity[] = $machineCapacity[$i] * $playerCapacity[$i];
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

        return view('Player.fitur.warehouse_machine', [
            'player' => $player,
            'room' => $room,
            'machine' => $machineChosen,
            'machineName' => $machineChosenName,
            'machinePrice' => $machinePrice,
            'playerMachineCapacity' => $playerMachineCapacity,
            'usedCapacity' => $totalCapacity
        ]);
    }
    public function join(Request $request)
    {
        $player = Player::where('player_username', Auth::guard('player')->user()->player_username)->first();

        $request->validate([
            'roomCode' => 'required|max:3'
        ]);

        $room = Room::where('room_id', $request->roomCode)->first();

        if (!$room) {
            return back()->with('error', 'Invalid Room Code');
        }

        $player = Player::firstWhere('player_username', Auth::guard('player')->user()->player_username);
        $player->room_id = $room->room_id;
        $player->save();

        PlayerJoin::dispatch();

        $room = Room::where('room_id', $request->roomCode)->first();

        return view('Player.fitur.lobby', [
            'room' => $room,
            'player' => $player
        ]);
    }

    public function profile($roomCode)
    {

        $room = Room::where('room_id', $roomCode)->first();
        $player = Player::where('player_username',Auth::guard('player')->user()->player_username)->first();
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

        $itemChosenName = [];
        foreach ($itemChosen as $item) {
            $query = Items::where('id', $item)->first();
            $itemChosenName[] = $query->item_name;
        }

        // Id Mesin Yang Dipakai
        $machineChosen = [];
        foreach ($itemChosen as $item) {
            $query = Machine::where('machine_item_index', $item)->first();
            $machineChosen[] = $query->id;
        }

        // Nama Mesin
        $machineChosenName = [];
        foreach ($machineChosen as $machine) {
            $query = Machine::where('id', $machine)->first();
            $machineChosenName[] = $query->machine_name;
        }

        // Kapasitas Produksi Mesin
        $machineCapacity = [];
        foreach ($machineChosen as $machine){
            $queryForMachine = Machine::where('id', $machine)->first();
            $machineCapacity[] = $queryForMachine->production_capacity;
        }

        $playerMachineCapacity = [];
        $playerCapacity = json_decode(Auth::guard('player')->user()->machine_capacity);

        // Kapasitas Produksi Player
        for ($i = 0; $i < count($machineCapacity);$i++){
            $playerMachineCapacity[] = $machineCapacity[$i] * $playerCapacity[$i];
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

        return view('Player.fitur.player_profile', [
            'player' => $player,
            'room' => $room,
            'roomRawItem' => $rawItemChosen,
            'playerRawItem' => json_decode(Auth::guard('player')->user()->raw_items),
            'roomItem' => $itemChosen,
            'roomItemName' => $itemChosenName,
            'playerItemQty' => json_decode(Auth::guard('player')->user()->items),
            'roomMachine' => $machineChosen,
            'roomMachineName' => $machineChosenName,
            'playerMachineCapacity' => $playerMachineCapacity,
            'usedCapacity' => $totalCapacity
        ]);
    }

    public function production($roomCode)
    {
        $room = Room::where('room_id', $roomCode)->first();
        $player = Player::where('player_username',Auth::guard('player')->user()->player_username)->first();
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

        $itemChosenName = [];
        foreach ($itemChosen as $item) {
            $query = Items::where('id', $item)->first();
            $itemChosenName[] = $query->item_name;
        }

        // Id Mesin Yang Dipakai
        $machineChosen = [];
        foreach ($itemChosen as $item) {
            $query = Machine::where('machine_item_index', $item)->first();
            $machineChosen[] = $query->id;
        }

        // Nama Mesin
        $machineChosenName = [];
        foreach ($machineChosen as $machine) {
            $query = Machine::where('id', $machine)->first();
            $machineChosenName[] = $query->machine_name;
        }

        // Kapasitas Produksi Mesin
        $machineCapacity = [];
        foreach ($machineChosen as $machine){
            $queryForMachine = Machine::where('id', $machine)->first();
            $machineCapacity[] = $queryForMachine->production_capacity;
        }

        $playerMachineCapacity = [];
        $playerCapacity = json_decode(Auth::guard('player')->user()->machine_capacity);

        // Kapasitas Produksi Player
        for ($i = 0; $i < count($machineCapacity);$i++){
            $playerMachineCapacity[] = $machineCapacity[$i] * $playerCapacity[$i];
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

        return view('Player.fitur.production',[
            'player' => $player ,
            'room' => $room,
            'roomRawItem' => $rawItemChosen,
            'playerRawItem' => json_decode(Auth::guard('player')->user()->raw_items),
            'roomItem' => $itemChosen,
            'roomItemName' => $itemChosenName,
            'playerItemQty' => json_decode(Auth::guard('player')->user()->items),
            'roomMachine' => $machineChosen,
            'roomMachineName' => $machineChosenName,
            'playerMachineCapacity' => $playerMachineCapacity,
            'currentCapacity' => $totalCapacity
        ]);
    }

    public function updateRevenue(Request $request)
    {
        $player = Player::where('player_username', $request->player_id)->first();

        if ($player) {
            return response()->json([
                'revenue' => $player->revenue,
                'debt' => $player->debt,
                'jatuh_tempo' => $player->jatuh_tempo
            ]);
        }

        return response()->json(['error' => 'Player not found'], 404);
    }
}
