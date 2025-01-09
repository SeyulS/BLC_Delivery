<?php

namespace App\Http\Controllers;
use App\Models\Player;

use Illuminate\Http\Request;

class RemovePlayerController extends Controller
{
    public function index(){
        return view('Admin.login');
    }

    public function remove(Request $request)
    {
        $validatedData = $request->validate([
            'playerId' => 'required',
        ]);

        $player = Player::firstWhere('player_username',$validatedData['playerId']);

        if (!$player) {
            return back()->with('fail', '!');
        }

        $player->room_id = null;
        $player->save();

        return back()->with('success', 'lol');
    }
}
