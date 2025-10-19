<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Follow\FollowUserRequest;
use App\Models\Follow;
use App\Services\FollowService;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\CrudService;
use Laravolt\Crud\Traits\CanFormatResource;
use Spatie\RouteDiscovery\Attributes\Route;

class FollowController extends BaseController
{
    use CanFormatResource;

    /**
     * @throws Exception
     */
    #[Route(method: 'post')]
    public function followUser(FollowUserRequest $request): JsonResource
    {
        /** @var FollowService $service */
        $service = $this->service();

        return $this->single($service->followUser($request));
    }

    protected function service(): CrudService
    {
        return new FollowService($this->model());
    }

    public function model(): CrudModel
    {
        return new Follow;
    }
}
