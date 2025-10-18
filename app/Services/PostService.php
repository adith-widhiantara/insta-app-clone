<?php

namespace App\Services;

use Illuminate\Foundation\Http\FormRequest;
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
}
