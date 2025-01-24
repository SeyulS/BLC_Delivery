<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManageDataController extends Controller
{
    public function index(){
        return view('Admin.manage_data');
    }

}
