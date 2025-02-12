<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Models\Player;

use Illuminate\Http\Request;

class RegistPlayerController extends Controller
{
    public function index()
    {
        return view('Admin.manage_account', [
            'players' => Player::all()
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'player_username' => ['required', 'min:3', 'max:255', 'unique:players'],
            'password' => ['required', 'min:5', 'max:255'],
            'confirmation_password' => ['required', 'same:password']
        ]);
        

        $validatedData['password'] = bcrypt($validatedData['password']);
        Player::create($validatedData);

        return redirect('/manageAccount')->with('success', 'Registration Successfull!! Please Login');
    }

    public function destroy(Request $request)
    {
        $player = Player::where('player_username',$request->input('player_username'))->first();

        if (!$player) {
            return response()->json(['success' => false, 'message' => 'Player not found'], 404);
        } else {
            $player->delete();
            return response()->json(['success' => true, 'message' => 'Player deleted successfully']);
        }
    }
}
