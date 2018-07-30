<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use App\Ranking;
use App\Player;

class PlayerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShouldListPlayers()
    {
        $ranking = factory(Ranking::class)->create();
        Passport::actingAs($ranking->user);
        $this->json('GET', "/api/ranking/{$ranking->id}/player")
            ->assertOk()
            ->assertJsonCount(0);
    }

    public function testShouldShowOnePlayer()
    {
        $player = factory(Player::class)->create();
        Passport::actingAs($player->ranking->user);
        $this->json('GET', "/api/ranking/{$player->ranking->id}/player/{$player->id}")
            ->assertOk()
            ->assertJsonFragment([ 'id' => $player->id ]);
    }

    public function testShouldCreateAPlayer()
    {
        $ranking = factory(Ranking::class)->create();
        Passport::actingAs($ranking->user);
        $this->json('POST', "/api/ranking/{$ranking->id}/player", ['name' => 'player name'])
            ->assertStatus(201)
            ->assertJsonFragment(['name' => 'player name']);
    }

    public function testShouldUpdateOnePlayer()
    {
        $player = factory(Player::class)->create();
        Passport::actingAs($player->ranking->user);
        $this->json('PUT',
            "/api/ranking/{$player->ranking->id}/player/{$player->id}",
            ['name' => 'updated'])
            ->assertOk()
            ->assertJsonFragment(['message' => 'Player updated!']);
    }

    public function testShouldDeleteOnePlayer()
    {
        $player = factory(Player::class)->create();
        $ranking = $player->ranking;
        Passport::actingAs($ranking->user);
        $this->json('DELETE',
            "/api/ranking/{$ranking->id}/player/{$player->id}")
            ->assertOk()
            ->assertJsonFragment(['message' => 'Player deleted!']);
        $this->assertEquals(0, $ranking->players()->count());
    }
}
