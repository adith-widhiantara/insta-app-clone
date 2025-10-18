<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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

    public function test_success_register(): void
    {
        $response = $this->postJson('api/auth/register', [
            'name' => 'Test Name',
            'email' => 'email@name.com',
            'phone' => 'phonephone',
            'password' => 'password'
        ]);

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'token',
                    'user' => [
                        'name',
                        'password'
                    ]
                ]
            ]);

        $this->assertTrue(Hash::check('password', $response->json('data.user.password')));

        $this->assertDatabaseHas((new User())->getTable(), [
            'id' => $response->json('data.user.id'),
            'name' => $response->json('data.user.name'),
        ]);
    }

    public function test_failed_register_email_is_registered(): void
    {
        $email = 'email@name.com';

        $this->createUser([
            'email' => $email
        ]);

        $response = $this->postJson('api/auth/register', [
            'name' => 'Test Name',
            'email' => $email,
            'phone' => 'phonephone',
            'password' => 'password'
        ]);

        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'email'
            ]);
    }

    public function test_failed_register_phone_is_registered(): void
    {
        $phone = 'phonephone';

        $this->createUser([
            'phone' => $phone
        ]);

        $response = $this->postJson('api/auth/register', [
            'name' => 'Test Name',
            'email' => 'email@name.com',
            'phone' => $phone,
            'password' => 'password'
        ]);

        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'phone'
            ]);
    }
}
