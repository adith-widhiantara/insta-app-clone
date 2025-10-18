<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_success_login(): void
    {
        $password = 'password';

        $user = $this->createUser([
            'password' => $password
        ]);

        $response = $this->postJson('api/auth/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'token',
                    'user' => [
                        'email'
                    ]
                ]
            ]);
    }

    public function test_failed_login_wrong_password(): void
    {
        $user = $this->createUser([
            'password' => 'password'
        ]);

        $response = $this->postJson('api/auth/login', [
            'email' => $user->email,
            'password' => 'other-password',
        ]);

        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertEquals('Wrong password.', $response->json('message'));
    }
}
