<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginAdminController extends Controller
{
    public function index(){

        return view('Admin.login', []);
    }

    public function authenticate(Request $request){
        $creds = $request->validate([
            'admin_username' => 'required',
            'password' => 'required'
        ]);
    
        if (Auth::guard('administrator')->attempt($creds)){
            $request->session()->regenerate();

            return redirect()->intended('/blc-delivery/homeAdmin');  
        }

        return back()->with('loginError', 'Login Failed');
    }

    public function logout(Request $request){   
        Auth::guard('administrator')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/blc-delivery');
    }
}