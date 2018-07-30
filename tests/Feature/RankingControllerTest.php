<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Ranking;
use App\User;

class RankingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShouldListThePlayers()
    {
        $ranking = factory(Ranking::class)->create();
        $this->actingAs($ranking->user)
            ->get("/ranking/{$ranking->id}")
            ->assertStatus(200)
            ->assertSee($ranking->name);
    }

    public function testShouldCreateARanking()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user)
            ->post('/ranking', ['name' => 'ranking name']);
        
        $this->assertEquals(1, $user->rankings()->count());
    }

    public function testShouldUpdateARanking()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user)
            ->post('/ranking', ['name' => 'ranking name']);
        
        $ranking = $user->rankings()->first();
        $this->actingAs($user)
            ->put("/ranking/{$ranking->id}", ['name' => 'updated']);
        
        
        $this->assertEquals(
            1,
            $user->rankings()->where('name', 'updated')->count()
        );
    }

    public function testShouldDeleteARanking()
    {
        $ranking = factory(Ranking::class)->create();
        $this->assertEquals(1, $ranking->user->rankings()->count());
        $this->actingAs($ranking->user)->delete("/ranking/{$ranking->id}");
        $this->assertEquals(0, $ranking->user->rankings()->count());
    }
}
