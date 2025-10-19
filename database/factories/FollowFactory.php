<?php

namespace Database\Factories;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Follow>
 */
class FollowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = User::query()->pluck('id');

        $followerId = $this->faker->randomElement($userIds);

        do {
            $followingId = $this->faker->randomElement($userIds);
        } while ($followerId === $followingId);

        return [
            'follower_id' => $followerId,
            'following_id' => $followingId,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function relationship(int $followerId, int $followingId): Factory
    {
        return $this->state(fn (array $attributes) => [
            'follower_id' => $followerId,
            'following_id' => $followingId,
        ]);
    }
}
