<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'game_key'
    ];

    public function mathes()
    {
        return $this->hasMany('App\Match');
    }
}
