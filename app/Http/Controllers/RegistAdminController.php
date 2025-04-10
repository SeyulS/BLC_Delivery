<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistAdminController extends Controller
{
    public function index()
    {
        return view('Admin.manage_admin', [
            'administrator' => Auth::guard('administrator')->user(),
            'listOfAdmin' => Administrator::all()
        ]);
    }

    public function store(Request $request)
    {

        if (Auth::guard('administrator')->user()->super_admin == 0) {
            return redirect('/manageAdmin')->with('error', 'You are not allowed to do this action !!');
        }

        $validatedData = $request->validate([
            'admin_username' => ['required', 'min:3', 'max:255', 'unique:administrators'],
            'password' => ['required', 'min:5', 'max:255'],
            'confirmation_password' => ['required', 'same:password']
        ]);


        $validatedData['password'] = bcrypt($validatedData['password']);
        $admin = new Administrator();
        $admin->admin_username = $validatedData['admin_username'];
        $admin->super_admin = $request->input('super_admin');
        $admin->password = $validatedData['password'];
        $admin->save();

        return redirect('/manageAdmin')->with('success', 'Registration Successfull!! Please Login');
    }

    public function destroy(Request $request)
    {
        // Check if the user is a super admin
        if (Auth::guard('administrator')->user()->super_admin == 0) {
            return response()->json([
                'success' => false,
                'message' => 'You are not allowed to do this action !!'
            ]);
        }

        $admin = Administrator::where('admin_username', $request->input('admin_username'))->first();

        if (!$admin) {
            return response()->json(['success' => false, 'message' => 'Admin not found'], 404);
        } else {
            $admin->delete();
            return response()->json(['success' => true, 'message' => 'Admin deleted successfully']);
        }
    }
}
