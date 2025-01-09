<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;

class LobbyRoomController_Admin extends Controller
{
    public function index(Request $request)
    {
        dd(Player::all('player_username'));
        if (!$request->ajax()) return view('player.index');

        return response()->json([
            'data' => Player::all('player_username'),
        ]);
    }
}
