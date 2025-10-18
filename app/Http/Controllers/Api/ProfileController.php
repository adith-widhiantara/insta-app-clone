<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use Laravolt\Crud\CrudModel;
use Illuminate\Http\Request;
use Laravolt\Crud\CrudService;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\BaseController;
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
        return new User();
    }
}
