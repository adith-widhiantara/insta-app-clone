<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;
use Mockery;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public $seed = true;

    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    protected function getToken(array $attributes = []): string
    {
        $user = $this->createUser($attributes);

        return $user->createToken('TestToken')->plainTextToken;
    }

    protected function createUser(array $attributes = []): User
    {
        return User::factory()->create($attributes);
    }
}
