<?php

namespace Tests\Feature\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LikeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_like_entry_successfully_created_in_db(): void
    {
        $user = $this->createUser();

        $token = $user->createToken('test')->plainTextToken;

        $post = Post::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $response = $this
            ->withHeaders([
                'Authorization' => 'Bearer '.$token,
            ])
            ->postJson('api/like', [
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);

        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => [
                    'user_id',
                    'post_id',
                ],
            ]);

        $this->assertDatabaseHas((new Like)->getTable(), [
            'id' => $response->json('data.id'),
        ]);
    }
}
