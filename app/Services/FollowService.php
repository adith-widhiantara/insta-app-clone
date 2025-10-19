<?php

namespace App\Services;

use App\Http\Requests\Request;
use App\Models\User;
use Laravolt\Crud\CrudModel;

class FollowService extends Service
{
    public function followUser(Request $request): CrudModel
    {
        /** @var User $currentUser */
        $currentUser = User::query()
            ->where('id', auth()->id())
            ->with(['following'])
            ->firstOrFail();

        $userToFollow = User::query()
            ->where('id', $request->input('user_id'))
            ->firstOrFail();

        $currentUser->following()->attach($userToFollow->id);

        $currentUser->refresh();

        return $currentUser;
    }
}
