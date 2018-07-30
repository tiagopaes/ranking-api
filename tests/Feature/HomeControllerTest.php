<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShouldListTheRankings()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user)
            ->get('/home')
            ->assertStatus(200)
            ->assertSee('Rankings');
    }
}
