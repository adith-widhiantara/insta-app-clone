<?php

namespace App\Services;

use App\Models\Like;
use Illuminate\Support\Collection;
use Laravolt\Crud\Contracts\StoreRequestContract;

class LikeService extends Service
{
    public function toggleLike(StoreRequestContract $request): Collection
    {
        $like = Like::query()
            ->where('post_id', $request->input('post_id'))
            ->where('user_id', $request->input('user_id'))
            ->first();

        if (! $like) {
            $like = $this->create($request);
        } else {
            $this->delete($like);
        }

        return collect([
            'id' => $like?->id,
            'user_id' => $like?->user_id,
            'post_id' => $like?->post_id,
        ]);
    }
}
