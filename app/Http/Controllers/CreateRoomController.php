<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Room;
use Illuminate\Http\Request;

class CreateRoomController extends Controller
{
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'roomCode' => 'required|max:3|',
        ]);

        $room = Room::firstWhere('room_id', $validatedData['roomCode']);

        if (!$room) {
            $validatedData['admin_handle'] = Auth::guard('administrator')->user()->admin_username;
            $validatedData['room_id'] = $validatedData['roomCode'];

            // Create the Room
            Room::create($validatedData);

            return redirect('/homeAdmin')->with('success', 'Room created successfully!');
        }

        return redirect('/homeAdmin')->with('error', 'Duplicate Room Number');
    }
}
