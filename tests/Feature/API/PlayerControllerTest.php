<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use App\Ranking;
use App\Player;
use App\User;

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

    public function testShouldNotShowOnePlayerOfAnotherUser()
    {
        $player = factory(Player::class)->create();
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $this->json('GET', "/api/ranking/{$player->ranking->id}/player/{$player->id}")
            ->assertStatus(404)
            ->assertSee('not found');
    }

    public function testShouldCreateAPlayer()
    {
        $ranking = factory(Ranking::class)->create();
        Passport::actingAs($ranking->user);
        $this->json('POST', "/api/ranking/{$ranking->id}/player", ['name' => 'player name'])
            ->assertStatus(201)
            ->assertJsonFragment(['name' => 'player name']);
    }

    public function testShouldNotCreateAPlayer()
    {
        $ranking = factory(Ranking::class)->create();
        Passport::actingAs($ranking->user);
        $this->json('POST', "/api/ranking/{$ranking->id}/player")
            ->assertStatus(400);
    }

    public function testShouldNotCreatePlayerWithDuplicatedName()
    {
        $player = factory(Player::class)->create();
        Passport::actingAs($player->ranking->user);
        $this->json(
            'POST',
            "/api/ranking/{$player->ranking->id}/player",
            ['name' => $player->name])
            ->assertStatus(422)
            ->assertSee('The name has already been taken');
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

    public function testShouldNotUpdateOnePlayer()
    {
        $player = factory(Player::class)->create();
        Passport::actingAs($player->ranking->user);
        $this->json('PUT',
            "/api/ranking/{$player->ranking->id}/player/{$player->id}")
            ->assertStatus(400);
    }

    public function testShouldNotUpdateOnePlayerOfAnotherUser()
    {
        $player = factory(Player::class)->create();
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $this->json('PUT',
            "/api/ranking/{$player->ranking->id}/player/{$player->id}",
            ['name' => 'updated'])
            ->assertStatus(404)
            ->assertSee('not found');
    }

    public function testShouldUpdatePlayerWithDuplicatedName()
    {
        $ranking = factory(Ranking::class)->create();
        $player1 = $ranking->players()->create(['name' => 'player 1']);
        $player2 = $ranking->players()->create(['name' => 'player 2']);
        Passport::actingAs($ranking->user);
        $this->json(
            'PUT',
            "/api/ranking/{$ranking->id}/player/{$player2->id}",
            ['name' => $player1->name])
            ->assertStatus(422)
            ->assertSee('The name has already been taken');
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

    public function testShouldNotDeleteOnePlayerOfAnotherUser()
    {
        $player = factory(Player::class)->create();
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $this->json('DELETE',
            "/api/ranking/{$player->ranking->id}/player/{$player->id}")
            ->assertStatus(404)
            ->assertSee('not found');
    }
}
