<?php

namespace App\Http\Controllers;

use App\Events\PlayerJoin;
use App\Models\Room;
use App\Models\Player;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class JoinRoomController extends Controller
{
    public function index($roomCode){

        if(Auth::guard('player')->user()->room_id != $roomCode){
            return redirect('player-lobby/'.Auth::guard('player')->user()->room_id);
        }
        return view('Player.lobby',[
            'room_id' => $roomCode,
            'player' => Player::where('room_id', $roomCode)->get()
        ]);
    }
    public function join(Request $request)
    {
        $request->validate([
            'roomCode' => 'required|max:3'
        ]);

        $room = Room::where('room_id', $request->roomCode)->first();

        if (!$room){
            return back()->with('error', 'Invalid Room Code');
        }

        $player = Player::firstWhere('player_username',Auth::guard('player')->user()->player_username);
        $player->room_id = $room->room_id;
        $player->save();

        PlayerJoin::dispatch();

        return redirect()->route('player.lobby', ['roomCode' => $room->room_id]);
        
    }
}
