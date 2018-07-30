<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Ranking;

class PlayerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShouldCreateAPlayer()
    {
        $ranking = factory(Ranking::class)->create();
        $this->actingAs($ranking->user)
            ->post("/ranking/{$ranking->id}/player", [
                'name' => 'player name'
            ]);
        $this->assertEquals(1, $ranking->players()->count());
    }
}
