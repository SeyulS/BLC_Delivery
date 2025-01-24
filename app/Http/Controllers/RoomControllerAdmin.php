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
        $playerId = $request->input('player_id');

        if (!$playerId) {
            return response()->json(['message' => 'Player ID is required'], 400);
        }

        $player = Player::find($playerId);

        if (!$player) {
            return response()->json(['message' => 'Player not found'], 404);
        }

        $player->room_id = null;
        $player->pinjaman_id = null;
        $player->inventory = null;
        $player->raw_items = null;
        $player->items = null;
        $player->revenue = 0;
        $player->jatuh_tempo = 0;
        $player->debt = 0;
        $player->save();
        PlayerRemove::dispatch();

        return response()->json(['message' => 'Player successfully removed'], 200);
    }
}
