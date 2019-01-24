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

    public function testShouldNotShowOneRankingOfAnotherUser()
    {
        $ranking = factory(Ranking::class)->create();
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $this->json('GET', "/api/ranking/{$ranking->id}")
            ->assertStatus(404)
            ->assertSee('not found');
    }

    public function testShouldCreateARanking()
    {
        Passport::actingAs(factory(User::class)->create());
        $this->json('POST', '/api/ranking', ['name' => 'ranking name'])
            ->assertStatus(201)
            ->assertJsonFragment(['name' => 'ranking name']);
    }

    public function testShouldNotCreateARanking()
    {
        Passport::actingAs(factory(User::class)->create());
        $this->json('POST', '/api/ranking')
            ->assertStatus(400);
    }

    public function testShouldNotAllowDuplicatedNamesWhenCreatingRanking()
    {
        $ranking = factory(Ranking::class)->create();
        Passport::actingAs($ranking->user);
        $this->json('POST', '/api/ranking', ['name' => $ranking->name])
            ->assertStatus(422)
            ->assertSee('The name has already been taken');
    }

    public function testShouldUpdateOneRanking()
    {
        $ranking = factory(Ranking::class)->create();
        Passport::actingAs($ranking->user);
        $this->json('PUT', "/api/ranking/{$ranking->id}", ['name' => 'updated'])
            ->assertOk()
            ->assertJsonFragment(['message' => 'Ranking updated!']);
    }

    public function testShouldNotUpdateOneRankingOfAnotherUser()
    {
        $ranking = factory(Ranking::class)->create();
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $this->json('PUT', "/api/ranking/{$ranking->id}", ['name' => 'updated'])
            ->assertStatus(404)
            ->assertSee('not found');
    }

    public function testShouldNotAllowDuplicatedNamesWhenUpdatingRanking()
    {
        $user = factory(User::class)->create();
        $user->rankings()->create(['name' => 'ranking 1']);
        $ranking2 = $user->rankings()->create(['name' => 'ranking 2']);
        Passport::actingAs($user);
        $this->json(
            'PUT',
            "/api/ranking/{$ranking2->id}",
            ['name' => 'ranking 1']
        )->assertStatus(422)
        ->assertSee('The name has already been taken');
    }

    public function testShouldNotUpdateARanking()
    {
        $ranking = factory(Ranking::class)->create();
        Passport::actingAs($ranking->user);
        $this->json('PUT', "/api/ranking/{$ranking->id}")
            ->assertStatus(400);
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

    public function testShouldNotDeleteOneRankingOfAnotherUser()
    {
        $ranking = factory(Ranking::class)->create();
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $this->json('DELETE', "/api/ranking/{$ranking->id}")
            ->assertStatus(404)
            ->assertSee('not found');
    }
}
