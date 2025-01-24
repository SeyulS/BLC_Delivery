<?php

namespace App\Http\Controllers;

use App\Events\NextDaySimulation;
use App\Events\PauseSimulation;
use App\Events\StartSimulation;
use App\Models\Items;
use App\Models\Machine;
use App\Models\Player;
use App\Models\Raw_item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

use App\Models\Room;

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
                    'machine_capacity' => json_encode($arr3)
                ]);

            StartSimulation::dispatch();

            return redirect()->back()->with('success', 'Simulation has started');
        } else {
            return redirect()->back()->with('error', 'Room not found');
        }
    }

    public function pause(Request $request)
    {
        $room = Room::where('room_id', $request->input('room_id'))->first();

        if ($room) {
            $room->status = 0;
            $room->save();

            PauseSimulation::dispatch();
            return redirect()->back()->with('success', 'Simulation has paused');
        } else {
            return redirect()->back()->with('error', 'Room not found');
        }
    }

    public function resume(Request $request)
    {
        $room = Room::where('room_id', $request->input('room_id'))->first();

        if ($room) {
            $room->status = 1;
            $room->save();

            StartSimulation::dispatch();

            return redirect()->back()->with('success', 'Simulation has been resumed');
        } else {
            return redirect()->back()->with('error', 'Room not found');
        }
    }

    public function nextDay(Request $request)
    {
        $room = Room::where('room_id', $request->input('room_id'))->first();

        if ($room) {

            // Hitung Denda (Apa saja yang ada di invetory)

            // Player bisa produce lagi

            // Cek apa ada player yang pinjaman jatuh tempo
            
            // 

            // Ganti Hari
            $room->recent_day = $room->recent_day + 1;
            $room->save();



            NextDaySimulation::dispatch();
            return redirect()->back()->with('success', 'The day has changed');
        } else {
            return redirect()->back()->with('error', 'Room not found');
        }
    }

    public function end(Request $request)
    {
        $room = Room::where('room_id', $request->input('room_id'))->first();
    }
}
