<?php

namespace Tests\Feature;

use Tests\TestCase;
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

    public function testShouldShowTheCreateForm()
    {
        $ranking = factory(Ranking::class)->create();
        $this->actingAs($ranking->user)
            ->get("/ranking/{$ranking->id}/player/create")
            ->assertOk()
            ->assertSee('New Player');
    }

    public function testShouldAllowDuplicatedNamesWhenCreatingPlayer()
    {
        $ranking = factory(Ranking::class)->create();
        $ranking->players()->create(['name' => 'player name']);
        $this->actingAs($ranking->user)
            ->post(
                "/ranking/{$ranking->id}/player",
                ['name' => 'player name']
            );
        $this->actingAs($ranking->user)
            ->get('/home')
            ->assertOk()
            ->assertSee('Player created');
    }
}
