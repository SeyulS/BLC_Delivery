<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Models\Player;

use Illuminate\Http\Request;

class RegistPlayerController extends Controller
{
    public function index(){
        return view('Player.register');
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'player_username' => ['required', 'min:3', 'max:255', 'unique:players'],
            'password' => 'required|min:5|max:255'
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);
        Player::create($validatedData);
        
        return redirect('/')->with('success','Registration Successfull!! Please Login');
    }
}
