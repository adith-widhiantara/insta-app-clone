<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Services\ProfileService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\CrudService;
use Spatie\RouteDiscovery\Attributes\Route;

class ProfileController extends BaseController
{
    /**
     * @throws Exception
     */
    #[Route(method: 'get', uri: 'my-profile')]
    public function myProfile(Request $request): JsonResponse
    {
        /** @var ProfileService $service */
        $service = $this->service();

        return ResponseFormatter::success(
            data: $service->myProfile($request)
        );
    }

    protected function service(): CrudService
    {
        return new ProfileService($this->model());
    }

    public function model(): CrudModel
    {
        return new User;
    }
}
