<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_followers_method(): void
    {
        $user = new User;

        $this->assertInstanceOf(BelongsToMany::class, $user->followers());
    }

    public function test_posts_method(): void
    {
        $user = new User;

        $this->assertInstanceOf(HasMany::class, $user->posts());
    }
}
