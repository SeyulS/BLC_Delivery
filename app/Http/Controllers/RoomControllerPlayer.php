<?php

namespace App\Http\Controllers;
use App\Models\Player;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class RoomControllerPlayer extends Controller
{
    public function index()
    {
        return view('Player.lobby',[
            'room_id' => Auth::guard('player')->user()->room_id,
            'players' => Player::where('room_id', Auth::guard('player')->user()->room_id)->get()
        ]);

    }
}
