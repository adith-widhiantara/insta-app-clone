<?php

namespace App\Http\Requests\Follow;

use App\Http\Requests\Request;
use App\Models\User;
use Illuminate\Validation\Rule;

class FollowUserRequest extends Request
{
    public function rules(): array
    {
        return [
            'user_id' => [$this->required, Rule::exists((new User)->getTable(), 'id')],
        ];
    }
}
