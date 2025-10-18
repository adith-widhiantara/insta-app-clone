<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Post\StoreRequest;
use App\Models\Post;
use App\Services\PostService;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\CrudService;

class PostController extends BaseController
{
    protected string $storeRequest = StoreRequest::class;

    protected function service(): CrudService
    {
        return new PostService($this->model());
    }

    public function model(): CrudModel
    {
        return new Post;
    }
}
