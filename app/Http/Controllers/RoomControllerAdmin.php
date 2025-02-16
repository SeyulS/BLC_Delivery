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
        try {
            $request->validate([
                'player_username' => 'required|string',
                'room_id' => 'required'
            ]);

            $player = Player::where('player_username', $request->player_username)->first();

            if (!$player) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Player not found'
                ], 404);
            }

            // Update player's room status
            $player->update(['room_id' => null]);

            return response()->json([
                'status' => 'success',
                'message' => 'Player has been kicked from the room'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
