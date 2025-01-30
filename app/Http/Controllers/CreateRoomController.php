<?php

namespace App\Http\Controllers;

use App\Models\DeckDemand;
use App\Models\Decks;
use App\Models\Machine;
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
            'item1' => 'nullable|exists:items,id',
            'item2' => 'nullable|exists:items,id',
            'item3' => 'nullable|exists:items,id',
            'deck' => 'nullable|exists:deck,id',
            'warehouseSize' => 'required|integer',
            'warehousePrice' => 'required|numeric',
            'inventoryCost' => 'required|numeric',
            'lateDeliveryCost' => 'required|numeric'
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
        $room->late_delivery_charge = $validatedData['lateDeliveryCost'];
        $room->inventory_cost = $validatedData['inventoryCost'];
        $room->status = 0;

        $machine1 = Machine::where('machine_item_index',$validatedData['item1'])->first();
        $machine2 = Machine::where('machine_item_index',$validatedData['item2'])->first();
        $machine3 = Machine::where('machine_item_index',$validatedData['item3'])->first();

        $machineIndex = [
            $machine1->machine_item_index,
            $machine2->machine_item_index,
            $machine3->machine_item_index
        ];
        $room->machine_chosen = json_encode($machineIndex); 
        $room->save();

        // Deck yang digunakan
        $deck = Decks::where('id', $room->deck_id)->first();
        $listOfDemand = json_decode($deck->deck_list);
        foreach ($listOfDemand as $demand){
            $deck_demand = new DeckDemand();
            $deck_demand->deck_id = $deck->id;
            $deck_demand->demand_id = $demand;
            $deck_demand->room_id = $validatedData['roomCode'];
            $deck_demand->player_username = null;
            $deck_demand->save();
        }

        return redirect()->back()->with('success', 'Room created successfully');
    }
}
