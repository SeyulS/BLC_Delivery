<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use Illuminate\Http\Request;

class RegistAdminController extends Controller
{
    public function index(){
        return view('Admin.register');
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'admin_username' => ['required', 'min:3', 'max:255', 'unique:administrators'],
            'password' => 'required|min:5|max:255'
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);
        Administrator::create($validatedData);
        
        return redirect('/')->with('success','Registration Successfull!! Please Login');
    }
}
