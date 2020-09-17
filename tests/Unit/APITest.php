<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class APITest extends TestCase
{
    public function testGameStore()
    {
        $this->json('post','/api/game/store', ['player' => 'X', 'square' => 2])
             ->assertJson([
                'success' => true,
                'log' => 'X is on square 2'
            ]);
    }

    public function testMatchStore()
    {
        $this->json('post','/api/match/store', ['player' => 'O', 'square' => 6])
             ->assertJson([
                'success' => true,
                'log' => 'O is on square 6'
            ]);
    }

    public function testGameReset()
    {
        $this->json('post','/api/game/reset')
             ->assertJson([
                'success' => true
            ]);
    }
}
