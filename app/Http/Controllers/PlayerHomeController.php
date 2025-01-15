<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\Room;
class PlayerHomeController extends Controller
{
    public function index(){
        if(Auth::guard('player')->user()->room_id == null){
            return view('Player.home');
        }
        $room = Room::where('room_id', Auth::guard('player')->user()->room_id)->first();
        return view('Player.fitur.lobby', [
            'roomCode' => $room->room_id,
            'roomStatus' => $room->status
        ]);
    }
}
