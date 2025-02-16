<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use Illuminate\Http\Request;
use App\Events\PlayerRemove;

use App\Models\Player;

class RoomControllerAdmin extends Controller
{
    public function index($room_id)
    {
        $room = Room::where('room_id', $room_id)->first();
        return view('Admin.fitur.lobby', [
            'room' => $room
        ]);
    }

    public function getPlayers($room_id)
    {
        $query = Player::where('room_id', $room_id);

        if ($search = request('search')['value']) {
            $query->where('player_username', 'like', '%' . $search . '%');
        }

        $totalRecords = Player::where('room_id', $room_id)->count();

        $totalFiltered = $query->count();

        $start = request('start', 0);
        $length = request('length', 10);
        $players = $query->skip($start)->take($length)->get();


        return response()->json([
            'data' => $players,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
        ]);
    }

    public function kickPlayer(Request $request)
    {

        if (!$request->input('player_username')) {
            return response()->json(['message' => 'Player ID is required'], 400);
        }

        $player = Player::where('player_username', $request->input('player_username'))->first();
        $roomId = $player->room_id;
        if (!$player) {
            return response()->json(['message' => 'Player not found'], 404);
        }

        $player->room_id = null;
        $player->inventory = null;
        $player->raw_items = null;
        $player->items = null;
        $player->machine_capacity = null;
        $player->revenue = null;
        $player->jatuh_tempo = null;
        $player->debt = null;
        $player->produce = null;
        $player->save();
        PlayerRemove::dispatch($player->player_username, $roomId);

        return response()->json(['message' => 'Player successfully removed'], 200);
    }
}
