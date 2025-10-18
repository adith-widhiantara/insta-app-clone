<?php

namespace App\Services;

use Illuminate\Http\Request;

class ProfileService extends Service
{
    public function myProfile(Request $request): array
    {
        $user = $request->user();

        return [
            'user' => $user,
        ];
    }
}
