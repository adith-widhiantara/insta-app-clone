<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Post\StoreRequest;
use App\Models\Post;
use App\Services\PostService;
use Exception;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\CrudService;
use Laravolt\Crud\Traits\CanFormatResource;
use Spatie\RouteDiscovery\Attributes\Route;

class PostController extends BaseController
{
    use CanFormatResource;

    protected string $storeRequest = StoreRequest::class;

    /**
     * @throws Exception
     */
    #[Route(method: 'get', uri: 'my-post')]
    public function myPost(): ResourceCollection
    {
        /** @var PostService $service */
        $service = $this->service();

        return $this->collection($service->myPost());
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
