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
        return view('Admin.fitur.pinjaman', [
            'room_id' => $room_id,
            'players' => Player::where('room_id', $room_id)->get(),
            'pinjaman' => Pinjaman::all()
        ]);
    }

    public function settingPinjaman(Request $request){
        $team = $request->team;
        $pinjaman = $request->pinjaman;
        $room_id = $request->room_id;

        $queryPlayer = Player::where('player_username',$team)->first();
        $queryPinjaman = Pinjaman::where('pinjaman_id',$pinjaman)->first();
        $queryRoom = Room::where('room_id', $room_id)->first();
        // dd($team);

        if($queryPlayer->pinjaman_id == null){
            $pinjaman_value = $queryPinjaman->pinjaman_value;
            $bunga_pinjaman = $queryPinjaman->bunga_pinjaman;
            $lama_pinjaman = $queryPinjaman->lama_pinjaman;
            

            // Hutang
            $debt = $pinjaman_value + ($pinjaman_value*$bunga_pinjaman);

        $queryPlayer->pinjaman_id = $pinjaman;
            $queryPlayer->revenue = $queryPlayer->revenue + $pinjaman_value;
            $queryPlayer->jatuh_tempo = $queryRoom->day + $lama_pinjaman;
            $queryPlayer->debt = $debt;

            $queryPlayer->save();

            UpdateRevenue::dispatch();

            return back()->with('success', 'Setting Success');

        }
        else{
            return back()->with('fail', 'There is still on going debt !');
        }
    }


}
