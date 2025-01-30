<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Player;
use App\Models\Demand;
use App\Models\Room;

class SettingPengirimanController extends Controller
{
    public function indexLCL($room_id){
        $room = Room::where('room_id', $room_id)->first();
        return view('Admin.fitur.setting_pengiriman_lcl',[
            'room' => $room,
            'players' => Player::where('room_id', $room_id)->get()

        ]);
    }

    public function indexFCL($room_id){
        return view('Admin.fitur.setting_pengiriman_fcl',[
            'room_id' => $room_id,
            'players' => Player::where('room_id', $room_id)->get()
        ]);

    }

    public function getDemand($username){
        $demands = Demand::where('player_username',$username)->get();
        if($demands){
            return response()->json($demands);
        }
        return response()->json(['error' => 'No Demand'], 404);
    }
}
