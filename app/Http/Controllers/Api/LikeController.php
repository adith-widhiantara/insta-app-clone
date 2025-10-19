<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Like;
use App\Services\LikeService;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\CrudService;

class LikeController extends BaseController
{
    protected function service(): CrudService
    {
        return new LikeService($this->model());
    }

    public function model(): CrudModel
    {
        return new Like;
    }
}
