<?php

namespace App\Http\Controllers;

use App\Models\Decks;
use App\Models\Demand;
use Illuminate\Http\Request;

class DeckController extends Controller
{
    public function index()
    {

        return view('Admin.deck_list', [
            'decks' => Decks::all()
        ]);
    }

    public function manage($deck_id)
    {
        $deck = Decks::where('deck_id', $deck_id)->first();

        $demand_ids = json_decode($deck->deck_list) ?? [];

        $demands = Demand::whereIn('demand_id', $demand_ids)->get();

        return view('Admin.manage_deck', [
            'deck_id' => $deck_id,
            'deck_name' => $deck->deck_name,
            'demands' => $demands,
        ]);
    }
}
