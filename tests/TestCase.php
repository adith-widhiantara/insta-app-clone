<?php

namespace Tests;

use Mockery;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public $seed = true;

    public function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();
    }

    public function tearDown(): void
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
