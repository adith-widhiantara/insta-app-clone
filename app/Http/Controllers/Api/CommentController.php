<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Comment;
use App\Services\CommentService;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\CrudService;

class CommentController extends BaseController
{
    protected function service(): CrudService
    {
        return new CommentService($this->model());
    }

    public function model(): CrudModel
    {
        return new Comment;
    }
}
