<?php

namespace App\Http\Requests\Post;

use App\Http\Requests\Request;

class StoreRequest extends Request
{
    public function rules(): array
    {
        $rules = parent::rules();

        unset($rules['image_url']);

        return array_merge($rules, [
            'image' => [$this->required, 'file'],
        ]);
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => $this->user()->id,
        ]);
    }
}
