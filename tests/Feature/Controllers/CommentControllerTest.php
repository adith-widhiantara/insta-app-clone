<?php

namespace Tests\Feature\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_comment_entry_successfully_saved_to_db(): void
    {
        $user = $this->createUser();

        $token = $user->createToken('test')->plainTextToken;

        $post = Post::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $text = fake()->text();

        $response = $this
            ->withHeaders([
                'Authorization' => 'Bearer '.$token,
            ])
            ->postJson('api/comment', [
                'user_id' => $user->id,
                'post_id' => $post->id,
                'content' => $text,
            ]);

        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => [
                    'user_id',
                    'post_id',
                    'content',
                ],
            ]);

        $this->assertDatabaseHas((new Comment)->getTable(), [
            'id' => $response->json('data.id'),
            'content' => $text,
        ]);
    }
}
