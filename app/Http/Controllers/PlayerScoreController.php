<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\SimulationResult;
use Illuminate\Http\Request;

class PlayerScoreController extends Controller
{
    public function index($roomCode)
    {
        $room = Room::where('room_id', $roomCode)->first();
        
        // Get top 5 players sorted by score
        $results = SimulationResult::where('room_id', $roomCode)
            ->orderBy('score', 'desc')
            ->get();

        // Add ranking badges
        $ranks = ['🥇 1st Place', '🥈 2nd Place', '🥉 3rd Place', '4th Place', '5th Place'];
        $results = $results->map(function ($item, $key) use ($ranks) {
            $item->rank = $ranks[$key] ?? '';
            return $item;
        });

        return view('Admin.fitur.player_score', [
            'room' => $room,
            'result' => $results
        ]);
    }
}