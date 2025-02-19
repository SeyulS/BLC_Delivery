<?php

namespace App\Http\Controllers;

use App\Events\PlayerJoin;
use App\Models\AirplaneDelivery;
use App\Models\DeckDemand;
use App\Models\Demand;
use App\Models\FCLDelivery;
use App\Models\Items;
use App\Models\LCLDelivery;
use App\Models\Machine;
use App\Models\Player;
use App\Models\RawItem;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class RoomControllerPlayer extends Controller
{
    // Join Room
    public function index($roomCode)
    {
        $room = Room::where('room_id', $roomCode)->first();
        if (Auth::guard('player')->user() == null) {
            return redirect('/loginPlayer');
        }

        if (Auth::guard('player')->user()->room_id == null){
            return view('Player.home');
        }

        if ($room->finished == 1) {
            return view('Player.home');
        }

        $player = Player::where('player_username', Auth::guard('player')->user()->player_username)->first();
        return view('Player.fitur.lobby', [
            'room' => $room,
            'player' => $player
        ]);
    }

    public function warehouseMachine($roomCode)
    {
        if (Auth::guard('player')->user() == null) {
            return redirect('/loginPlayer');
        }

        if (Auth::guard('player')->user()->room_id == null){
            return view('Player.home');
        }
        
        $room = Room::where('room_id', $roomCode)->first();
        if ($room->finished == 1) {
            return view('Player.home');
        }

        $player = Player::where('player_username', Auth::guard('player')->user()->player_username)->first();
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

        for ($i = 0; $i < count($machineCapacity); $i++) {
            $playerMachineCapacity[] = $machineCapacity[$i] * $playerCapacity[$i];
        }

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
        if (Auth::guard('player')->user() == null) {
            return redirect('/loginPlayer');
        }
        $room = Room::where('room_id', $request->roomCode)->first();

        $request->validate([
            'roomCode' => 'required|max:3'
        ]);

        if (!$room) {
            return back()->with('error', 'Invalid Room Code');
        }

        if ($room->start == 1) {
            return back()->with('error', 'Room Simulation has started');
        }

        if ($room->finished == 1) {
            return back()->with('error', 'Room Simulation has finished');
        }

        $player = Player::where('player_username', Auth::guard('player')->user()->player_username)->first();
        $player = Player::firstWhere('player_username', Auth::guard('player')->user()->player_username);
        $player->room_id = $room->room_id;
        $player->save();

        PlayerJoin::dispatch($room->room_id);

        return view('Player.fitur.lobby', [
            'room' => $room,
            'player' => $player
        ]);
    }
    public function calendar($roomCode)
    {
        if (Auth::guard('player')->user() == null) {
            return redirect('/loginPlayer');
        }

        if (Auth::guard('player')->user()->room_id == null){
            return view('Player.home');
        }

        $room = Room::where('room_id', $roomCode)->first();

        if($room->finished == 1){
            return view('Player.home');
        }

        $player = Player::where('player_username', Auth::guard('player')->user()->player_username)->first();

        return view('Player.fitur.calendar', [
            'room' => $room,
            'player' => $player
        ]);
    }

    public function profile($roomCode)
    {
        if (Auth::guard('player')->user() == null) {
            return redirect('/loginPlayer');
        }

        if (Auth::guard('player')->user()->room_id == null){
            return view('Player.home');
        }

        $room = Room::where('room_id', $roomCode)->first();
        if($room->finished == 1){
            return view('Player.home');
        }
        $player = Player::where('player_username', Auth::guard('player')->user()->player_username)->first();
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
        $rawItemChosen = RawItem::whereIn('id', $rawItem)->get();

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
        foreach ($machineChosen as $machine) {
            $queryForMachine = Machine::where('id', $machine)->first();
            $machineCapacity[] = $queryForMachine->production_capacity;
        }

        $playerMachineCapacity = [];
        $playerCapacity = json_decode(Auth::guard('player')->user()->machine_capacity);

        // Kapasitas Produksi Player
        for ($i = 0; $i < count($machineCapacity); $i++) {
            $playerMachineCapacity[] = $machineCapacity[$i] * $playerCapacity[$i];
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

        // Demands
        $demands = Demand::where('room_id', $room->room_id)
            ->where('player_username', $player->player_username)->get();

        if ($player->jatuh_tempo == null) {
            $jatuh_tempo = null;
        } else {
            $jatuh_tempo = $player->jatuh_tempo - $room->recent_day;
        }
        return view('Player.fitur.player_profile', [
            'player' => $player,
            'jatuh_tempo' => $jatuh_tempo,
            'room' => $room,
            'roomRawItem' => $rawItemChosen,
            'playerRawItem' => json_decode(Auth::guard('player')->user()->raw_items),
            'roomItem' => $itemChosen,
            'roomItemName' => $itemChosenName,
            'playerItemQty' => json_decode(Auth::guard('player')->user()->items),
            'roomMachine' => $machineChosen,
            'roomMachineName' => $machineChosenName,
            'playerMachineCapacity' => $playerMachineCapacity,
            'usedCapacity' => $totalCapacity,
            'demands' => $demands,
            'currentDateTime' => now()->format('Y-m-d H:i:s'),
        ]);
    }

    public function production($roomCode)
    {
        if (Auth::guard('player')->user() == null) {
            return redirect('/loginPlayer');
        }

        if (Auth::guard('player')->user()->room_id == null){
            return view('Player.home');
        }

        $room = Room::where('room_id', $roomCode)->first();
        if($room->finished == 1){
            return view('Player.home');
        }
        $player = Player::where('player_username', Auth::guard('player')->user()->player_username)->first();
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
        $rawItemChosen = RawItem::whereIn('id', $rawItem)->get();

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
        foreach ($machineChosen as $machine) {
            $queryForMachine = Machine::where('id', $machine)->first();
            $machineCapacity[] = $queryForMachine->production_capacity;
        }

        $playerMachineCapacity = [];
        $playerCapacity = json_decode(Auth::guard('player')->user()->machine_capacity);

        // Kapasitas Produksi Player
        for ($i = 0; $i < count($machineCapacity); $i++) {
            $playerMachineCapacity[] = $machineCapacity[$i] * $playerCapacity[$i];
        }

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

        return view('Player.fitur.production', [
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
            'currentCapacity' => $totalCapacity
        ]);
    }

    public function showDemand($roomCode)
    {
        if (Auth::guard('player')->user() == null) {
            return redirect('/loginPlayer');
        }

        if (Auth::guard('player')->user()->room_id == null){
            return view('Player.home');
        }

        $room = Room::where('room_id', $roomCode)->first();

        if($room->finished == 1){
            return view('Player.home');
        }
        $player = Player::where('player_username', Auth::guard('player')->user()->player_username)->first();

        $demands = Demand::where('room_id', $room->room_id)
            ->where('day', $room->recent_day)->get();

        $demands = $demands->map(function ($demand) use ($room) {
            $demand->taken = Demand::where('room_id', $room->room_id)
                ->where('day', $room->recent_day)
                ->where('demand_id', $demand->demand_id)
                ->whereNull('player_username')
                ->exists() ? false : true;
            return $demand;
        });

        $items = Room::where('room_id', $roomCode)->first()->item_chosen;
        $itemName = [];
        foreach (json_decode($items) as $item) {
            $query = Items::where('id', $item)->first();
            $itemName[] = $query;
        }

        return view('Player.fitur.list_of_demands', [
            'player' => $player,
            'room' => $room,
            'demands' => $demands,
            'uniqueDestinations' => ['Manado', 'Banjarmasin', 'Makassar'],
            'uniqueItems' => $itemName
        ]);
    }

    public function marketIntelligence($roomCode)
    {
        if (Auth::guard('player')->user() == null) {
            return redirect('/loginPlayer');
        }

        if (Auth::guard('player')->user()->room_id == null){
            return view('Player.home');
        }

        $room = Room::where('room_id', $roomCode)->first();

        if($room->finished == 1){
            return view('Player.home');
        }
        $player = Player::where('player_username', Auth::guard('player')->user()->player_username)->first();

        $items = $room->item_chosen;
        $itemName = [];
        $BOM = [];  // Initialize the BOM array
        foreach (json_decode($items) as $item) {
            $query = Items::where('id', $item)->first();

            $rawItems = [];

            $rawItemsNeeded = json_decode($query->raw_item_needed);
            $quantityItemsNeeded = json_decode($query->raw_quantity_needed);

            for ($i = 0; $i < count($rawItemsNeeded); $i++) {
                $rawItems[] = [
                    'name' => RawItem::where('id', $rawItemsNeeded[$i])->first()->raw_item_name,
                    'quantity' => $quantityItemsNeeded[$i]
                ];
            }

            $BOM[] = [
                'item_name' => $query->item_name,
                'raw_items' => $rawItems,
                'width' => $query->item_width,
                'length' => $query->item_length,
                'height' => $query->item_height,
                'weight' => $query->item_weight
            ];
        }
        // dd($BOM);

        $machine = $room->machine_chosen;
        $machineName = [];
        foreach (json_decode($machine) as $machine) {
            $query = Machine::where('id', $machine)->first();
            $machineName[] = $query;
        }
        $rawItem = [];

        foreach (json_decode($items) as $item) {
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

        return view('Player.fitur.market_intelligence', [
            'player' => $player,
            'room' => $room,
            'machines' => $machineName,
            'rawItems' => RawItem::whereIn('id', array_unique($rawItem))->get(),
            'lateDeliveryCharge' => $room->late_delivery_charge,
            'earlyDeliveryCharge' => $room->early_delivery_charge,
            'inventoryCost' => $room->inventory_cost,
            'warehouseSize' => $room->warehouse_size,
            'warehousePrice' => $room->warehouse_price,
            'specialDays' => json_decode($room->special_day),
            'maxDay' => $room->max_day,
            'BOM' => $BOM,
            'lcl' => LCLDelivery::where('room_id', $room->room_id)->get(),
            'fcl' => FCLDelivery::where('room_id', $room->room_id)->get(),
            'air' => AirplaneDelivery::where('room_id', $room->room_id)->get()
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

    public function payingOffDebt($roomCode)
    {
        if (Auth::guard('player')->user() == null) {
            return redirect('/loginPlayer');
        }

        if (Auth::guard('player')->user()->room_id == null){
            return view('Player.home');
        }

        $room = Room::where('room_id', $roomCode)->first();
        return view('Player.fitur.paying_debt', [
            'player' => Auth::guard('player')->user(),
            'room' => $room
        ]);
    }

    
}
