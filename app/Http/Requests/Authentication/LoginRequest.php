<?php

namespace App\Http\Requests\Authentication;

use App\Models\User;
use App\Http\Requests\Request;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;

final class LoginRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => [$this->required, Rule::exists((new User())->getTable(), 'email')],
            'password' => [$this->required]
        ];
    }
}
