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

        $currentUser->following()->attach($userToFollowId);

        $currentUser->refresh();

        return $currentUser;
    }

    /**
     * @throws ValidationException
     */
    public function unfollowUser(Request $request): CrudModel
    {
        $userId = auth()->id();
        $userToFollowId = $request->input('user_id');

        $follow = Follow::query()
            ->where([
                'follower_id' => $userId,
                'following_id' => $userToFollowId,
            ])
            ->exists();

        if (! $follow) {
            throw ValidationException::withMessages([
                'following_id' => 'You not follow this user',
            ]);
        }

        /** @var User $currentUser */
        $currentUser = User::query()
            ->where('id', $userId)
            ->with(['following'])
            ->firstOrFail();

        $currentUser->following()->detach($userToFollowId);

        $currentUser->refresh();

        return $currentUser;
    }
}
