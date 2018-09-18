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
            ->assertOk()
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
        $this->actingAs($ranking->user)
            ->delete("/ranking/{$ranking->id}");
        $this->assertEquals(0, $ranking->user->rankings()->count());
    }

    public function testShouldShowTheCreateForm()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user)
            ->get('/ranking/create')
            ->assertOk()
            ->assertSee('New Ranking');
    }

    public function testShouldShowTheRankingPlayersList()
    {
        $ranking = factory(Ranking::class)->create();
        $ranking->players()->create(['name' => 'player name']);

        $this->actingAs($ranking->user)
            ->get("/ranking/{$ranking->id}")
            ->assertOk()
            ->assertSee('player name');
    }

    public function testShouldShowTheEditForm()
    {
        $ranking = factory(Ranking::class)->create();
        $this->actingAs($ranking->user)
            ->get("/ranking/{$ranking->id}/edit")
            ->assertOk()
            ->assertSee('Edit Ranking');
    }

    public function testShouldNotAllowDuplicatedNamesWhenCreatingRanking()
    {
        $user = factory(User::class)->create();
        $user->rankings()->create(['name' => 'simple name']);
        $this->actingAs($user)
            ->post('/ranking', ['name' => 'simple name']);
        
        $this->actingAs($user)
            ->get('/home')
            ->assertOk()
            ->assertSee('The name has already been taken');
    }

    public function testShouldNotAllowDuplicatedNamesWhenUpdatingRanking()
    {
        $user = factory(User::class)->create();
        $user->rankings()->create(['name' => 'simple name 1']);
        $ranking2 = $user->rankings()->create(['name' => 'simple name 2']);
        $this->actingAs($user)
            ->put("/ranking/{$ranking2->id}", ['name' => 'simple name 1']);
        
        $this->actingAs($user)
            ->get('/home')
            ->assertOk()
            ->assertSee('The name has already been taken');
    }
}
