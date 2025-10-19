<?php

namespace App\Services;

use App\Http\Requests\Request;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Laravolt\Crud\CrudModel;

class FollowService extends Service
{
    /**
     * @throws ValidationException
     */
    public function followUser(Request $request): CrudModel
    {
        $userId = auth()->id();
        $userToFollowId = $request->input('user_id');

        $follow = Follow::query()
            ->where([
                'follower_id' => $userId,
                'following_id' => $userToFollowId,
            ])
            ->exists();

        if ($follow) {
            throw ValidationException::withMessages([
                'following_id' => 'Already followed',
            ]);
        }

        /** @var User $currentUser */
        $currentUser = User::query()
            ->where('id', $userId)
            ->with(['following'])
            ->firstOrFail();

        $userToFollow = User::query()
            ->where('id', $userToFollowId)
            ->firstOrFail();

        $currentUser->following()->attach($userToFollow->id);

        $currentUser->refresh();

        return $currentUser;
    }
}
