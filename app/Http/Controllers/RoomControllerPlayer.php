<?php

namespace App\Http\Controllers;

use App\Events\PlayerJoin;
use App\Models\Items;
use App\Models\Player;
use App\Models\Raw_item;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class RoomControllerPlayer extends Controller
{
    public function index($roomCode)
    {
        $room = Room::where('room_id', $roomCode)->first();

        return view('Player.fitur.lobby', [
            'roomCode' => $roomCode,
            'roomStatus' => $room->status
        ]);
    }

    public function warehouseMachine($roomCode){
        $room = Room::where('room_id', $roomCode)->first();

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
        foreach ($itemChosen as $item){
            $query = Items::where('id', $item)->first();
            $itemChosenName[] = $query->item_name;
        }

        return view('Player.fitur.warehouse_machine', [
            'player' => Auth::guard('player')->user(),
            'roomCode' => $roomCode,
            'roomStatus' => $room->status,
            'roomRawItem' => $rawItemChosen,
            'playerRawItem' => json_decode(Auth::guard('player')->user()->raw_items),
            'roomItem' => $itemChosen,
            'roomItemName' => $itemChosenName,
            'playerItemQty' => json_decode(Auth::guard('player')->user()->items)
        ]);
    }
    public function join(Request $request)
    {
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
            'roomCode' => $room->room_id,
            'roomStatus' => $room->status
        ]);
    }

    public function profile($roomCode)
    {

        $room = Room::where('room_id', $roomCode)->first();

        return view('Player.fitur.player_profile', [
            'player' => Auth::guard('player')->user(),
            'roomCode' => $roomCode,
            'roomStatus' => $room->status
        ]);
    }

    public function updateRevenue(Request $request)
    {
        $player = Player::where('player_username', $request->player_id)->first();

        if ($player) {
            return response()->json(['revenue' => $player->revenue]);
        }
    
        return response()->json(['error' => 'Player not found'], 404);
    }
}
