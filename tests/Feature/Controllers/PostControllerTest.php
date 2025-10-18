<?php

namespace Tests\Feature\Controllers;

use App\Models\Post;
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
}
