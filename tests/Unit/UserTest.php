<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class UserTest extends TestCase
{
    public function testShouldCheckIfUserIsAdmin()
    {
        $user = new User();
        $user->type = 'admin';

        $this->assertTrue($user->isAdmin());
    }

    public function testShouldCheckIfUserIsNotAdmin()
    {
        $user = new User();
        $user->type = 'default';

        $this->assertFalse($user->isAdmin());
    }
}
