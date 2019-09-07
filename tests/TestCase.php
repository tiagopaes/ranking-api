<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function getAuthHeader(User $user)
    {
        return ['Authorization' => 'Bearer ' . $user->api_token];
    }
}
