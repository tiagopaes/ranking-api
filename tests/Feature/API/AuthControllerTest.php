<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShouldRegisterUser()
    {
        $this->json('POST', '/api/auth/register', [
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => '123456'
        ])->assertStatus(201);
    }

    public function testShouldLoginUser()
    {
        $user = factory(User::class)->create();
        $this->json('POST', '/api/auth/login', [
            'email' => $user->email,
            'password' => 'secret'
        ])->assertStatus(200);
    }
}
