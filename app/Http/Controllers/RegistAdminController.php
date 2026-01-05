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
            return redirect('/blc-delivery/manageAdmin')->with('error', 'You are not allowed to do this action !!');
        }

        $validator = \Validator::make($request->all(), [
            'admin_username' => ['required', 'min:3', 'max:255', 'unique:administrators'],
            'password' => ['required', 'min:5', 'max:255'],
            'confirmation_password' => ['required', 'same:password']
        ], [
            'admin_username.required' => 'Username is required',
            'admin_username.unique' => 'Username already exists',
            'password.required' => 'Password is required',
            'confirmation_password.same' => 'Passwords do not match'
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return redirect('/blc-delivery/manageAdmin')
                ->withErrors($validator) // Pass validation errors
                ->withInput() // Retain old input values
                ->with('error', 'Registration failed. Please check your input.'); // Custom error message
        }
    
        // If validation passes, proceed with creating the player
        $validatedData = $validator->validated();
        $validatedData['password'] = bcrypt($validatedData['password']);
        $admin = new Administrator();
        $admin->admin_username = $validatedData['admin_username'];
        $admin->password = $validatedData['password'];
        if($request->input('super_admin') == null){
            $admin->super_admin = 0;
        }else{
            $admin->super_admin = 1;
        }
        $admin->save();
        return redirect('/blc-delivery/manageAdmin')->with('success', 'Registration Successful!!');
    
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
