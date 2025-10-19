<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileService extends Service
{
    public function myProfile(Request $request): array
    {
        /** @var User $user */
        $user = $request->user();

        $user->load([
            'followers',
            'following',
        ]);

        return [
            'user' => $user,
        ];
    }
}
