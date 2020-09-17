<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Match;
use App\Game;
use Cookie;

class MatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $logs = Match::where('game_id', Cookie::get('the_key'))->get();

        return response()->json($logs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'player' => 'required|string|max:1|in:X,O',
            'square' => 'required|integer|between:1,9',
        ]);

        $player = $request->player;
        $square = $request->square;
        $game_key = Cookie::get('the_key');
        
        $match = new Match;
        $match->game_id = $game_key;
        $match->player = $player;
        $match->square = $square;
        $match->log = $player." is on square ".$square;
        $match->save();

        return response()->json(array('success' => true, 'log' => $match->log), 200);
    }
}
