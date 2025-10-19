<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Laravolt\Crud\Contracts\StoreRequestContract;

class PostService extends Service
{
    public function prepareCreateData(FormRequest|StoreRequestContract $request): array
    {
        $data = parent::prepareCreateData($request);

        $data['image_url'] = config('app.url').Storage::url($request->file('image')?->store('post', 'public'));

        unset($data['image']);

        return $data;
    }

    public function myPost(): LengthAwarePaginator
    {
        $data = Post::query()
            ->where('user_id', Auth::id())
            ->get();

        return new LengthAwarePaginator($data, $data->count(), $data->count(), 1);
    }

    public function appendQuery($query)
    {
        $query = parent::appendQuery($query);

        $userId = Auth::id();

        /** @var User $user */
        $user = User::find($userId);

        $user->load(['following']);

        $followedUserIds = $user->following()->pluck('following_id');

        $followedUserIds->push($userId);

        $query
            ->whereIn('user_id', $followedUserIds->toArray())
            ->with(['user']);

        return $query;
    }
}
