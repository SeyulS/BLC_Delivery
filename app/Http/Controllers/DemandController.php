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

        $query = DeckDemand::where('room_id', $request->input('room_id'))
            ->where('demand_id', $request->input('demand_id'))
            ->where('deck_id', 1)->first();

        if ($query->player_username != null) {
            return response()->json(['status' => 'fail', 'message' => 'Demand has been taken']);
        } else {
            $query->player_username = $request->input('player_id');
            $query->save();

            DemandTaken::dispatch($request->input('demand_id'));
            return response()->json(['status' => 'success', 'message' => 'Successfully took the demand']);
        }
    }

    public function getDemands(Request $request)
    {
        $playerUsername = $request->input('player_username');
        $player = Player::where('player_username', $playerUsername)->first();
        $room = Room::where('room_id', $player->room_id)->first();

        $demandIds = DeckDemand::where('player_username', $playerUsername)
            ->where('room_id', $room->room_id)
            ->where('deck_id', $room->deck_id)
            ->pluck('demand_id');

        $demandIdsArray = $demandIds->toArray();

        $demands = Demand::whereIn('demand_id', $demandIdsArray)->get();
        return response()->json($demands);
    }
}
