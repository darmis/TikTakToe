<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Game;
use App\Match;
use Illuminate\Support\Facades\Hash;
use Cookie;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Cookie::get('the_key')){
            $result = Match::where('game_id', Cookie::get('the_key'))->get(['square','player']);
            $last_player = Match::where('game_id', Cookie::get('the_key'))->latest('updated_at')->first(['player']);
            return response()->json(array('success' => true, 'result' => $result, 'lastPlayer' => $last_player), 200);
        } else {
            return response()->json(array('success' => true, 'result' => 'none'), 200);
        }
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

        $game = new Game;
        $game->save();
        $last_id = $game->id;

        $game = Game::where('id', $game->id)->first();
        $the_key = Hash::make($last_id);
        $game->game_key = $the_key;
        $game->save();   
        
        $player = $request->player;
        $square = $request->square;

        $match = new Match;
        $match->game_id = $the_key;
        $match->player = $player;
        $match->square = $square;
        $match->log = $player." is on square ".$square;
        $match->save();

        return response()->json(array('success' => true, 'the_key' => $the_key, 'log' => $match->log), 200)->cookie('the_key', $the_key, 60);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {        
        return response()->json(array('success' => true), 200)->withCookie(Cookie::forget('the_key'));
    }
}
