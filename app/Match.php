<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    protected $fillable = [
        'game_id',
        'log'
    ];
    
    public function game()
    {

        return $this->belongsTo('App\Game');
    }
}
