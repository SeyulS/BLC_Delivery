<?php

namespace App\Http\Controllers;

use App\Events\EndSimulation;
use App\Events\NextDaySimulation;
use App\Events\PauseSimulation;
use App\Events\PlayerRemove;
use App\Events\ResumeSimulation;
use App\Events\StartSimulation;
use App\Models\AirplaneDelivery;
use App\Models\Demand;
use App\Models\FCLDelivery;
use App\Models\Items;
use App\Models\LCLDelivery;
use App\Models\Machine;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

use App\Models\Room;
use App\Models\SimulationResult;

class UtilityRoomController extends Controller
{
    public function index($roomCode)
    {
        return view('Admin.fitur.utility_room', [
            'room_id' => $roomCode
        ]);
    }

    public function start(Request $request)
    {
        $room = Room::where('room_id', $request->input('room_id'))->first();

        if ($room) {
            $room->start = 1;
            $room->status = 1;
            $room->recent_day = 1;
            $room->save();

            $room = Room::where('room_id', $request->input('room_id'))->first();
            $itemChosen = json_decode($room->item_chosen, true);

            $rawItem = [];

            // Set Player Punya Raw Item sesuai raw item yang digunakan
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

            $rawItem = count(array_unique($rawItem));
            $arr = [];
            for ($i = 0; $i < $rawItem; $i++) {
                $arr[] = 0;
            }

            $item = count(array_unique($itemChosen));
            $arr2 = [];
            for ($i = 0; $i < $item; $i++) {
                $arr2[] = 0;
            }

            $machineChosen = [];
            foreach ($itemChosen as $item) {
                $query = Machine::where('machine_item_index', $item)->first();
                $machineChosen[] = $query->id;
            }

            $machine = count($machineChosen);
            $arr3 = [];
            for ($i = 0; $i < $machine; $i++) {
                $arr3[] = 0;
            }

            Player::where('room_id', $request->input('room_id'))
                ->update([
                    'raw_items' => json_encode($arr),
                    'items' => json_encode($arr2),
                    'revenue' => $room->first_capital,
                    'inventory' => 0,
                    'machine_capacity' => json_encode($arr3),
                    'jatuh_tempo' => null,
                    'debt' => null,
                    'produce' => 1,
                ]);

            StartSimulation::dispatch($room->room_id);

            return response()->json([
                'status' => 'success',
                'message' => 'Simulation has been started'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Simulation Room Not Found'
            ]);
        }
    }

    public function pause(Request $request)
    {
        $room = Room::where('room_id', $request->input('room_id'))->first();

        if ($room) {
            $room->status = 0;
            $room->save();

            PauseSimulation::dispatch($room->room_id);
            return response()->json([
                'status' => 'success',
                'message' => 'Simulation has been paused'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Simulation Room Not Found'
            ]);
        }
    }

    public function resume(Request $request)
    {
        $room = Room::where('room_id', $request->input('room_id'))->first();

        if ($room) {
            $room->status = 1;
            $room->save();

            ResumeSimulation::dispatch($room->room_id);
            return response()->json([
                'status' => 'success',
                'message' => 'Simulation has been resume'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Simulation Room Not Found'
            ]);
        }
    }

    public function nextDay(Request $request)
    {
        $room = Room::where('room_id', $request->input('room_id'))->first();
        if ($room->recent_day != $room->max_day) {
            $players = Player::where('room_id', $room->room_id)->get();
            foreach ($players as $player) {
                // Cek Jatuh Tempo
                if ($player->jatuh_tempo == $room->recent_day + 1) {
                    $player->revenue = $player->revenue - $player->debt;
                    $player->debt = null;
                    $player->jatuh_tempo = null;
                    $player->save();
                }
                $totalInventory = 0;
                $currentItemCapacity = json_decode($player->items);
                // $roomItemIndex = json_decode($room->item_chosen);

                foreach ($currentItemCapacity as $item) {
                    $totalInventory = $totalInventory + $item;
                }
                // for ($i = 0; $i < count($roomItemIndex); $i++) {
                //     $queryItem = Items::where('id', $roomItemIndex[$i])->first();
                //     $totalInventory = $totalInventory + ($currentItemCapacity[$i] * $queryItem->item_length * $queryItem->item_width);
                // }
                $inventoryDebt = $totalInventory * $room->inventory_cost;
                $player->revenue = $player->revenue - $inventoryDebt;
                $player->produce = 1;
                $player->save();
                
            }

            $lcl = LCLDelivery::where('room_id', $room->room_id)->get();
            foreach ($lcl as $l) {
                $l->current_volume_capacity = 0;
                $l->current_weight_capacity = 0;
                $l->save();
            }

            $air = AirplaneDelivery::where('room_id', $room->room_id)->get();
            foreach ($air as $a) {
                $a->current_volume_capacity = 0;
                $a->current_weight_capacity = 0;
                $a->save();
            }

            $room->recent_day = $room->recent_day + 1;
            $room->save();

            NextDaySimulation::dispatch($room->room_id);
            return response()->json([
                'status' => 'success',
                'message' => 'Simulation day has changed'
            ]);
        } else {
            $room->status = 0;
            $room->save();

            PauseSimulation::dispatch($room->room_id);
            return response()->json([
                'status' => 'success',
                'message' => 'Simulation has ended'
            ]);
        }
    }

    public function end(Request $request)
    {
        $room = Room::where('room_id', $request->input('room_id'))->first();
        $player = Player::where('room_id', $room->room_id)->get();

        foreach ($player as $p) {
            $evaluatedMachineValue = 0;
            $undeliveredDemandCharge = 0; // Akan mengurangi revenue peserta jika masih ada sisa demand di akhir permainan

            $machineChosen = json_decode($room->machine_chosen);
            $playerQuantityMachine = json_decode($p->machine_capacity);

            for ($i = 0; $i < count($machineChosen); $i++) {
                $machinePrice = Machine::where('id', $machineChosen[$i])->first()->machine_price;
                $evaluatedMachineValue = $evaluatedMachineValue + ($machinePrice * $playerQuantityMachine[$i] * 0.1);
            }

            $demand = Demand::where('player_username', $p->player_username)->get();
            foreach ($demand as $d) {
                $lateCharge = ($room->recent_day - $d->day) * $room->late_delivery_charge;
                $undeliveredDemandCharge = $undeliveredDemandCharge + ($d->revenue + $lateCharge);
            }

            $debt = $p->debt;
            if ($debt == null) {
                $debt = 0;
            }
            // Score = Revenue - Debt + evaluasi mesin - Value demand yang bellum terkirim
            $score = $p->revenue + $evaluatedMachineValue - ($debt + $undeliveredDemandCharge);

            $result = new SimulationResult();
            $result->room_id = $room->room_id;
            $result->player_username = $p->player_username;
            $result->score = $score;
            $result->save();
        }
        $room->status = 0;
        $room->finished = 1;
        $room->save();

        foreach ($player as $p) {
            $p->room_id = null;
            $p->inventory = null;
            $p->raw_items = null;
            $p->items = null;
            $p->machine_capacity = null;
            $p->revenue = null;
            $p->jatuh_tempo = null;
            $p->debt = null;
            $p->produce = null;
            $p->save();
        }

        EndSimulation::dispatch($room->room_id);
        return response()->json([
            'status' => 'success',
            'message' => 'Calculating Player Score has finished !!'
        ]);
    }
}
