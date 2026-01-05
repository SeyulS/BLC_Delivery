<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Models\Player;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistPlayerController extends Controller
{
    public function index()
    {
        return view('Admin.manage_account', [
            'administrator' => Auth::guard('administrator')->user(),
            'players' => Player::all()
        ]);
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'player_username' => ['required', 'min:3', 'max:255', 'unique:players'],
            'password' => ['required', 'min:5', 'max:255'],
            'confirmation_password' => ['required', 'same:password']
        ], [
            'player_username.required' => 'Username is required',
            'player_username.unique' => 'Username already exists',
            'password.required' => 'Password is required',
            'confirmation_password.same' => 'Passwords do not match'
        ]);

        if ($validator->fails()) {
            return redirect('/blc-delivery/manageAccount')
                ->withErrors($validator) 
                ->withInput()
                ->with('error', 'Validation failed. Please check your input.');
        }

        $validatedData = $validator->validated();
        $validatedData['password'] = bcrypt($validatedData['password']);
        Player::create($validatedData);

        return redirect('/blc-delivery/manageAccount')->with('success', 'Registration Successful!! Please Login');
    }

    public function destroy(Request $request)
    {
        $player = Player::where('player_username', $request->input('player_username'))->first();

        if (!$player) {
            return response()->json(['success' => false, 'message' => 'Player not found'], 404);
        } else {
            $player->delete();
            return response()->json(['success' => true, 'message' => 'Player deleted successfully']);
        }
    }
}
