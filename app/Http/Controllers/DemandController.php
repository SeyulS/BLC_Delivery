<?php

namespace App\Http\Controllers;

use App\Events\DemandTaken;
use App\Models\Demand;
use App\Models\DeckDemand;
use App\Models\Player;
use App\Models\Room;
use Illuminate\Http\Request;

class DemandController extends Controller
{
    public function takeDemand(Request $request)
    {
        $player = Player::where('player_username', $request->input('player_id'))->first();

        $room = Room::where('room_id', $request->input('room_id'))->first();

        if ($room->status == 0) {
            return response()->json(['status' => 'fail', 'message' => 'The simulation was paused']);
        }
        $query = Demand::where('room_id', $request->input('room_id'))
            ->where('demand_id', $request->input('demand_id'))
            ->where('day', $room->recent_day)->first();

        if ($query->player_username != null) {
            return response()->json(['status' => 'fail', 'message' => 'Demand has been taken']);
        } else {
            $query->player_username = $request->input('player_id');
            $query->save();

            $player->save();
            DemandTaken::dispatch($request->input('demand_id'));
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully took the demand',
                'demand_id' => $request->input('demand_id')
            ]);
        }
    }

    public function getDemands(Request $request)
    {
        $playerUsername = $request->input('player_username');
        $player = Player::where('player_username', $playerUsername)->first();
        $room = Room::where('room_id', $player->room_id)->first();

        $demands = Demand::where('player_username', $playerUsername)
            ->where('room_id', $room->room_id)
            ->where('is_delivered', '!=', true)
            ->get();
        return response()->json($demands);
    }

    public function getDemandsFCL(Request $request)
    {
        $playerUsername = $request->input('player_username');
        $player = Player::where('player_username', $playerUsername)->first();
        $room = Room::where('room_id', $player->room_id)->first();

        $demands = Demand::where('player_username', $request->input('player_username'))
            ->where('room_id', $room->room_id)
            ->where('tujuan_pengiriman', $request->input('destination'))
            ->where('is_delivered', '!=', true)
            ->get();

        if ($demands) {
            return response()->json($demands);
        }
        return response()->json(['error' => 'No Demand'], 404);
    }

    public function demandDeliveredInformation($room_id){
        return view('Admin.fitur.demand_information',[
            'demands' => Demand::where('room_id', $room_id)
                        ->where('is_delivered', true)
                        ->get(),
            'room' => Room::where('room_id', $room_id)->first()
        ]);
    }
}
