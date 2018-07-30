<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use App\User;
use App\Ranking;

class RankingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShouldListRankings()
    {
        Passport::actingAs(factory(User::class)->create());
        $this->json('GET', '/api/ranking')
            ->assertOk()
            ->assertJsonCount(0);
    }

    public function testShouldShowOneRanking()
    {
        $ranking = factory(Ranking::class)->create();
        Passport::actingAs($ranking->user);
        $this->json('GET', "/api/ranking/{$ranking->id}")
            ->assertOk()
            ->assertJsonFragment([ 'id' => $ranking->id ]);
    }

    public function testShouldCreateARanking()
    {
        Passport::actingAs(factory(User::class)->create());
        $this->json('POST', '/api/ranking', ['name' => 'ranking name'])
            ->assertStatus(201)
            ->assertJsonFragment(['name' => 'ranking name']);
    }

    public function testShouldUpdateOneRanking()
    {
        $ranking = factory(Ranking::class)->create();
        Passport::actingAs($ranking->user);
        $this->json('PUT', "/api/ranking/{$ranking->id}", ['name' => 'updated'])
            ->assertOk()
            ->assertJsonFragment(['message' => 'Ranking updated!']);
    }

    public function testShouldDeleteOneRanking()
    {
        $ranking = factory(Ranking::class)->create();
        $user = $ranking->user;
        Passport::actingAs($user);
        $this->json('DELETE', "/api/ranking/{$ranking->id}")
            ->assertOk()
            ->assertJsonFragment(['message' => 'Ranking deleted!']);
        $this->assertEquals(0, $user->rankings()->count());
    }
}
