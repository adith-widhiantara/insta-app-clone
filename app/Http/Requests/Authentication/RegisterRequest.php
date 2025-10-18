<?php

namespace App\Http\Requests\Authentication;

use App\Models\User;
use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class RegisterRequest extends Request
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'email' => [$this->required, Rule::unique((new User())->getTable(), 'email')],
            'phone' => [$this->required, Rule::unique((new User())->getTable(), 'phone')]
        ]);
    }

}
