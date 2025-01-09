<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class PlayerHomeController extends Controller
{
    public function index(){
        if(Auth::guard('player')->user()->room_id == null){
            return view('Player.home');
        }
        return redirect('player-lobby/'.Auth::guard('player')->user()->room_id);
    }
}
