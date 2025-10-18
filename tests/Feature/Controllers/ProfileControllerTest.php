<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_success_get_my_profile(): void
    {
        $name = 'Test Name';

        $token = $this->getToken([
            'name' => $name
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('api/profile/my-profile');

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'user' => [
                        'name'
                    ]
                ]
            ]);

        $this->assertEquals($name, $response->json('data.user.name'));
    }

    public function test_failed_get_my_profile(): void
    {
        $response = $this->getJson('api/profile/my-profile');

        $response
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonStructure([
                'message',
            ]);

        $this->assertEquals('Unauthenticated.', $response->json('message'));
    }
}
