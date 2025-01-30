<?php

namespace App\Http\Controllers;

use App\Events\UpdateRevenue;
use App\Models\Player;
use App\Models\Pinjaman;
use App\Models\Room;
use Illuminate\Http\Request;

class SettingPinjamanController extends Controller
{
    public function index($room_id)
    {
        $room = Room::where('room_id', $room_id)->first();
        return view('Admin.fitur.pinjaman', [
            'room' => $room,
            'players' => Player::where('room_id', $room_id)->get(),
            'pinjaman' => Pinjaman::all()
        ]);
    }

    public function settingPinjaman(Request $request){
        
        $player = Player::where('player_username', $request->input('player_username'))->first();

        if($player->debt == null){
            $player->revenue = $player->revenue + $request->input('loanAmount');
            $player->debt = $request->input('loanAmount') + ($request->input('loanAmount')*($request->input('loanInterest'))/100);
            $player->jatuh_tempo = $request->input('loanDuration');
            $player->save();

            UpdateRevenue::dispatch();
            return response()->json([
                'status' => 'success',
                'message' => 'Pinjaman Success',
            ]);
            
        }
        else{
            return response()->json([
                'status' => 'fail',
                'message' => 'There is still on going debt !!',
            ]);
        }

        
    }


}
