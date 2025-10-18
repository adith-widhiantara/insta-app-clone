<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Requests\Authentication\RegisterRequest;
use App\Models\User;
use App\Services\AuthenticationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\CrudService;

class AuthenticationController extends Controller
{
    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function login(LoginRequest $request): JsonResponse
    {
        /** @var AuthenticationService $service */
        $service = $this->service();

        return ResponseFormatter::success(
            data: $service->login($request)
        );
    }

    protected function service(): CrudService
    {
        return new AuthenticationService($this->model());
    }

    public function model(): CrudModel
    {
        return new User;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        /** @var AuthenticationService $service */
        $service = $this->service();

        return ResponseFormatter::success(
            data: $service->register($request),
            message: 'Success Register'
        );
    }
}
