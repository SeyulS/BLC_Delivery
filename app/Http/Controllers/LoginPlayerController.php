<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginPlayerController extends Controller
{
    public function index(){

        return view('Player.login', []);
    }

    public function authenticate(Request $request){
        $creds = $request->validate([
            'player_username' => 'required',
            'password' => 'required'
        ]);
    
        if (Auth::guard('player')->attempt($creds)){
            $request->session()->regenerate();

            return redirect()->intended('/blc-delivery/homePlayer');  
        }

        return back()->with('loginError', 'Login Failed');
    }

    public function logout(Request $request){   
        Auth::guard('player')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/blc-delivery');
    }
}