<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\Room;
use App\Models\Player;
use App\Models\Decks;

use Illuminate\Http\Request;

class AdminHomeController extends Controller
{
    public function index()
    {
        $rooms = Room::all()->map(function ($room) {
            $room->total_players = Player::where('room_id', $room->room_id)->count();
            return $room;
        });

        return view('Admin.home', [
            'rooms' => $rooms,
            'items' => Items::all(),
        ]);
    }
}