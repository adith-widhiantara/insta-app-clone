<?php

namespace App\Services;

use App\Http\Requests\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationService extends Service
{
    /**
     * @throws ValidationException
     */
    public function login(Request $request): array
    {
        /** @var User $user */
        $user = User::query()
            ->where('email', $request->input('email'))
            ->firstOrFail();

        if (! Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages(
                ['password' => 'Wrong password.']
            );
        }

        return $this->showUser($user);
    }

    private function showUser(User $user): array
    {
        return [
            'token' => $user->createToken('token')->plainTextToken,
            'user' => $user->toArray(),
        ];
    }

    public function register(Request $request): array
    {
        /** @var User $user */
        $user = User::query()
            ->create($request->all());

        return $this->showUser($user);
    }
}
