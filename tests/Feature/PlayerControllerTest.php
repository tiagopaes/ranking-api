<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Ranking;
use App\Player;
use App\User;

class PlayerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShouldListPlayers()
    {
        $ranking = factory(Ranking::class)->create();
        $this->withHeaders($this->getAuthHeader($ranking->user))
            ->json('GET', "/api/ranking/{$ranking->id}/player")
            ->assertOk()
            ->assertJsonCount(0);

        $ranking->players()->create(['name' => 'player 1']);
        $this->withHeaders($this->getAuthHeader($ranking->user))
            ->json('GET', "/api/ranking/{$ranking->id}/player")
            ->assertOk()
            ->assertJsonCount(1);
    }

    public function testShouldShowOnePlayer()
    {
        $player = factory(Player::class)->create();
        $this->withHeaders($this->getAuthHeader($player->ranking->user))
            ->json('GET', "/api/ranking/{$player->ranking->id}/player/{$player->id}")
            ->assertOk()
            ->assertJsonFragment(['id' => $player->id]);
    }

    public function testShouldNotShowOnePlayerOfAnotherUser()
    {
        $player = factory(Player::class)->create();
        $user = factory(User::class)->create();
        $this->withHeaders($this->getAuthHeader($user))
            ->json('GET', "/api/ranking/{$player->ranking->id}/player/{$player->id}")
            ->assertStatus(404)
            ->assertSee('not found');
    }

    public function testShouldCreateAPlayer()
    {
        $ranking = factory(Ranking::class)->create();
        $this->withHeaders($this->getAuthHeader($ranking->user))
            ->json('POST', "/api/ranking/{$ranking->id}/player", ['name' => 'player name'])
            ->assertStatus(201)
            ->assertJsonFragment(['name' => 'player name']);
    }

    public function testShouldNotCreateAPlayer()
    {
        $ranking = factory(Ranking::class)->create();
        $this->withHeaders($this->getAuthHeader($ranking->user))
            ->json('POST', "/api/ranking/{$ranking->id}/player")
            ->assertStatus(400);
    }

    public function testShouldCreatePlayerWithDuplicatedName()
    {
        $player = factory(Player::class)->create();
        $this->withHeaders($this->getAuthHeader($player->ranking->user))
            ->json(
                'POST',
                "/api/ranking/{$player->ranking->id}/player",
                ['name' => $player->name]
            )
            ->assertStatus(201);
    }

    public function testShouldUpdateOnePlayer()
    {
        $player = factory(Player::class)->create();
        $this->withHeaders($this->getAuthHeader($player->ranking->user))
            ->json(
                'PUT',
                "/api/ranking/{$player->ranking->id}/player/{$player->id}",
                ['name' => 'updated']
            )
            ->assertOk()
            ->assertJsonFragment(['message' => 'Player updated!']);
    }

    public function testShouldNotUpdateOnePlayer()
    {
        $player = factory(Player::class)->create();
        $this->withHeaders($this->getAuthHeader($player->ranking->user))
            ->json(
                'PUT',
                "/api/ranking/{$player->ranking->id}/player/{$player->id}"
            )
            ->assertStatus(400);
    }

    public function testShouldNotUpdateOnePlayerOfAnotherUser()
    {
        $player = factory(Player::class)->create();
        $user = factory(User::class)->create();
        $this->withHeaders($this->getAuthHeader($user))
            ->json(
                'PUT',
                "/api/ranking/{$player->ranking->id}/player/{$player->id}",
                ['name' => 'updated']
            )
            ->assertStatus(404)
            ->assertSee('not found');
    }

    public function testShouldUpdatePlayerWithDuplicatedName()
    {
        $ranking = factory(Ranking::class)->create();
        $player1 = $ranking->players()->create(['name' => 'player 1']);
        $player2 = $ranking->players()->create(['name' => 'player 2']);
        $this->withHeaders($this->getAuthHeader($ranking->user))
            ->json(
                'PUT',
                "/api/ranking/{$ranking->id}/player/{$player2->id}",
                ['name' => $player1->name]
            )
            ->assertOk();
    }

    public function testShouldDeleteOnePlayer()
    {
        $player = factory(Player::class)->create();
        $ranking = $player->ranking;
        $this->withHeaders($this->getAuthHeader($ranking->user))
            ->json(
                'DELETE',
                "/api/ranking/{$ranking->id}/player/{$player->id}"
            )
            ->assertOk()
            ->assertJsonFragment(['message' => 'Player deleted!']);
        $this->assertEquals(0, $ranking->players()->count());
    }

    public function testShouldNotDeleteOnePlayerOfAnotherUser()
    {
        $player = factory(Player::class)->create();
        $user = factory(User::class)->create();
        $this->withHeaders($this->getAuthHeader($user))
            ->json(
                'DELETE',
                "/api/ranking/{$player->ranking->id}/player/{$player->id}"
            )
            ->assertStatus(404)
            ->assertSee('not found');
    }

    public function testShouldListPlayersWithLimit()
    {
        $ranking = factory(Ranking::class)->create();
        $ranking->players()->create(['name' => 'player 1']);
        $ranking->players()->create(['name' => 'player 2']);
        $this->withHeaders($this->getAuthHeader($ranking->user))
            ->json('GET', "/api/ranking/{$ranking->id}/player?limit=1")
            ->assertOk()
            ->assertJsonCount(1);
    }
}
