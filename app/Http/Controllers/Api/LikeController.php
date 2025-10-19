<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Like;
use App\Services\LikeService;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravolt\Crud\Contracts\StoreRequestContract;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\CrudService;

class LikeController extends BaseController
{
    public function store(StoreRequestContract $request): JsonResource
    {
        /** @var LikeService $service */
        $service = $this->service();

        return $this->single($service->toggleLike($request));
    }

    protected function service(): CrudService
    {
        return new LikeService($this->model());
    }

    public function model(): CrudModel
    {
        return new Like;
    }
}
