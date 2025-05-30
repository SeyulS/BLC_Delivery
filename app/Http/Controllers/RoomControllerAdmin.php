<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use Illuminate\Http\Request;
use App\Events\PlayerRemove;
use App\Models\Loan;
use App\Models\Player;
use App\Models\RevenueHistory;

class RoomControllerAdmin extends Controller
{
    public function index($room_id)
    {
        $room = Room::where('room_id', $room_id)->first();

        $highestLoan = 0;
        $loan = Loan::where('room_id', $room_id)->get();
        foreach ($loan as $l) {
            if ($l->loan_value > $highestLoan) {
                $highestLoan = $l->loan_value;
            }
        }
        return view('Admin.fitur.lobby', [
            'room' => $room,
            'highestLoan' => $highestLoan
        ]);
    }

    public function getPlayers($room_id)
    {
        $query = Player::where('room_id', $room_id)
            ->orderBy('revenue', 'desc'); // Urutkan berdasarkan revenue secara descending

        // Filter pencarian jika ada
        if ($search = request('search')['value']) {
            $query->where('player_username', 'like', '%' . $search . '%');
        }

        // Hitung total data dan data yang difilter
        $totalRecords = Player::where('room_id', $room_id)->count();
        $totalFiltered = $query->count();

        // Pagination
        $start = request('start', 0);
        $length = request('length', 10);
        $players = $query->skip($start)->take($length)->get();

        // Kembalikan data dalam format JSON
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

    public function playerTransaction($room_id)
    {
        $room = Room::where('room_id', $room_id)->first();
        return view('Admin.fitur.player_transaction', [
            'room' => $room,
            'history' => RevenueHistory::where('room_id', $room_id)->get(),
            'players' => Player::where('room_id', $room_id)->get()
        ]);
    }
}
