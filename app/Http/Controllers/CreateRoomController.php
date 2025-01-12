<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Room;
use Illuminate\Http\Request;

class CreateRoomController extends Controller
{
    public function createRoom(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'roomCode' => 'required|max:3',
            'roomDescription' => 'required|string|max:255',
            'numDays' => 'required|integer|min:1',
            'specialDays' => 'nullable|array',
            'specialDays.*' => 'integer|min:1',
            'item1' => 'nullable|exists:items,item_id',
            'item2' => 'nullable|exists:items,item_id',
            'item3' => 'nullable|exists:items,item_id',
            'deck' => 'nullable|exists:deck,deck_id',
            'warehouseSize' => 'required|integer',
            'warehousePrice' => 'required|numeric',
        ]);

        $room = Room::where('room_id',$validatedData['roomCode']);

        $room = new Room();
        $room->room_id = $validatedData['roomCode'];
        $room->room_name = $validatedData['roomDescription'];
        $room->max_day = $validatedData['numDays'];
        $room->special_day = json_encode($validatedData['specialDays'] ?? []);
        $room->item_chosen = json_encode([
            $validatedData['item1'],
            $validatedData['item2'],
            $validatedData['item3'],
        ]);
        $room->deck_id = $validatedData['deck'];
        $room->warehouse_size = $validatedData['warehouseSize'];
        $room->warehouse_price = $validatedData['warehousePrice'];
        $room->save();

        return redirect()->back()->with('success', 'Room created successfully');
    }
}
