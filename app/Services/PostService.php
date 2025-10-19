<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
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

    public function myPost(): Collection|array
    {
        return Post::query()
            ->where('user_id', Auth::id())
            ->get();
    }
}
