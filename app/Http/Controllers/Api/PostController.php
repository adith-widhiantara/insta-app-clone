<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Post\StoreRequest;
use App\Models\Post;
use App\Services\PostService;
use Exception;
use Illuminate\Http\JsonResponse;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\CrudService;
use Spatie\RouteDiscovery\Attributes\Route;

class PostController extends BaseController
{
    protected string $storeRequest = StoreRequest::class;

    /**
     * @throws Exception
     */
    #[Route(method: 'get', uri: 'my-post')]
    public function myPost(): JsonResponse
    {
        /** @var PostService $service */
        $service = $this->service();

        return ResponseFormatter::success(
            data: $service->myPost(),
            message: 'My Post'
        );
    }

    protected function service(): CrudService
    {
        return new PostService($this->model());
    }

    public function model(): CrudModel
    {
        return new Post;
    }
}
