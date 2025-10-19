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

    public function test_verify_comment_text_length_limit(): void
    {
        $user = $this->createUser();

        $token = $user->createToken('test')->plainTextToken;

        $post = Post::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $text = fake()->text(500);

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
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'content',
            ]);
    }

    public function test_authorization_token_is_required(): void
    {
        $user = $this->createUser();

        $post = Post::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $text = fake()->text();

        $response = $this
            ->postJson('api/comment', [
                'user_id' => $user->id,
                'post_id' => $post->id,
                'content' => $text,
            ]);

        $response
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_delete_comment_succeeds_if_comment_owner_is_authenticated_user(): void
    {
        $user = $this->createUser();

        $token = $user->createToken('test')->plainTextToken;

        $post = Post::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $text = fake()->text();

        $comment = Comment::factory()
            ->create([
                'user_id' => $user->id,
                'post_id' => $post->id,
                'content' => $text,
            ]);

        $response = $this
            ->withHeaders([
                'Authorization' => 'Bearer '.$token,
            ])
            ->deleteJson('api/comment/'.$comment->id);

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data',
            ]);

        $this->assertDatabaseMissing((new Comment)->getTable(), [
            'id' => $comment->id,
        ]);
    }

    public function test_delete_comment_fails_with_access_denied_error_if_user_id_differs(): void
    {
        $user = $this->createUser();
        $userB = $this->createUser();

        $token = $user->createToken('test')->plainTextToken;

        $post = Post::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $text = fake()->text();

        $comment = Comment::factory()
            ->create([
                'user_id' => $userB->id,
                'post_id' => $post->id,
                'content' => $text,
            ]);

        $response = $this
            ->withHeaders([
                'Authorization' => 'Bearer '.$token,
            ])
            ->deleteJson('api/comment/'.$comment->id);

        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'user_id',
            ]);

        $this->assertDatabaseHas((new Comment)->getTable(), [
            'id' => $comment->id,
        ]);
    }
}
