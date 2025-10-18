<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\CrudService;
use Illuminate\Http\JsonResponse;
use App\Helpers\ResponseFormatter;
use App\Services\AuthenticationService;
use Spatie\RouteDiscovery\Attributes\Route;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Authentication\LoginRequest;

class AuthenticationController extends Controller
{
    /**
     * @throws ValidationException
     * @throws Exception
     */
    #[Route(method: 'post', uri: 'login')]
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
        return new User();
    }
}
