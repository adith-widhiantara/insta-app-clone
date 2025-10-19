<?php

namespace Tests\Feature\Controllers;

use App\Models\Post;
use Database\Factories\FollowFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_success_post_post_with_image_url(): void
    {
        Storage::fake('public');

        $existingFile = UploadedFile::fake()->image('existing.jpg');

        $user = $this->createUser();

        $token = $user->createToken('test')->plainTextToken;

        $text = fake()->text();

        $response = $this
            ->withHeaders([
                'Authorization' => 'Bearer '.$token,
            ])->postJson('api/post', [
                'image' => $existingFile,
                'caption' => $text,
            ]);

        $response
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertStringContainsString('http://', $response->json('data.image_url'));

        Storage::disk('public')->assertExists('post/'.$existingFile->hashName());

        $this->assertDatabaseHas((new Post)->getTable(), [
            'user_id' => $user->id,
            'caption' => $text,
        ]);
    }

    public function test_failed_post_post_unauthorized(): void
    {
        Storage::fake('public');

        $existingFile = UploadedFile::fake()->image('existing.jpg');

        $text = fake()->text();

        $response = $this
            ->postJson('api/post', [
                'image' => $existingFile,
                'caption' => $text,
            ]);

        $response
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_profile_endpoint_returns_only_authenticated_user_posts_data(): void
    {
        $user = $this->createUser();

        $token = $user->createToken('test')->plainTextToken;

        Post::factory()
            ->count(5)
            ->create();

        Post::factory()
            ->count(5)
            ->create([
                'user_id' => $user->id,
            ]);

        $response = $this
            ->withHeaders([
                'Authorization' => 'Bearer '.$token,
            ])
            ->getJson('api/post/my-post');

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'content' => [
                        '*' => [
                            'user_id',
                            'image_url',
                            'caption',
                        ],
                    ],
                ],
            ]);

        $this->assertCount(5, $response->json('data.content'));

        $this->assertDatabaseCount((new Post)->getTable(), 10);
    }

    public function test_feed_endpoint_returns_posts_based_on_following_logic(): void
    {
        Post::query()
            ->delete();

        $user = $this->createUser();
        $userB = $this->createUser();
        $userC = $this->createUser();

        $token = $user->createToken('test')->plainTextToken;

        FollowFactory::new()
            ->relationship($user->id, $userB->id)
            ->create();

        Post::factory()
            ->count(5)
            ->create();

        Post::factory()
            ->count(5)
            ->create([
                'user_id' => $user->id,
            ]);

        Post::factory()
            ->count(3)
            ->create([
                'user_id' => $userB->id,
            ]);

        Post::factory()
            ->count(3)
            ->create([
                'user_id' => $userC->id,
            ]);

        $response = $this
            ->withHeaders([
                'Authorization' => 'Bearer '.$token,
            ])
            ->getJson('api/post');

        /*
         * this include TC US06-BE-02
         */
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'content' => [
                        '*' => [
                            'user_id',
                            'image_url',
                            'caption',
                        ],
                    ],
                ],
            ]);

        $this->assertCount(8, $response->json('data.content'));

        $this->assertDatabaseCount((new Post)->getTable(), 16);
    }
}
