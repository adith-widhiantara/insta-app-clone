<?php

namespace Tests\Feature\Controllers;

use App\Models\Follow;
use Database\Factories\FollowFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class FollowControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_entry_successfully_created_in_follows_table(): void
    {
        $userA = $this->createUser();
        $userB = $this->createUser();

        $token = $this->createToken($userA);

        $response = $this
            ->withHeaders([
                'Authorization' => 'Bearer '.$token,
            ])
            ->postJson('api/follow/follow-user', [
                'user_id' => $userB->id,
            ]);

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'following' => [
                        '*' => [
                            'id',
                        ],
                    ],
                ],
            ]);

        $this->assertDatabaseHas((new Follow)->getTable(), [
            'follower_id' => $userA->id,
            'following_id' => $userB->id,
        ]);
    }

    public function test_follow_fails_if_relationship_already_exists_duplicate(): void
    {
        $userA = $this->createUser();
        $userB = $this->createUser();

        FollowFactory::new()
            ->relationship($userA->id, $userB->id)
            ->create();

        $this->assertDatabaseHas((new Follow)->getTable(), [
            'follower_id' => $userA->id,
            'following_id' => $userB->id,
        ]);

        $token = $this->createToken($userA);

        $response = $this
            ->withHeaders([
                'Authorization' => 'Bearer '.$token,
            ])
            ->postJson('api/follow/follow-user', [
                'user_id' => $userB->id,
            ]);

        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'following_id',
            ]);
    }
}
