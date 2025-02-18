<?php

namespace App\Http\Controllers;

use App\Models\Player;
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
        
        $player = Player::where('player_username', Auth::guard('player')->user()->player_username)->first();
        if($room){
            return view('Player.fitur.lobby', [
                'room' => $room,
                'player' => $player
            ]);
        }
        else{
            return view('Player.home');
        }
        
    }
}
