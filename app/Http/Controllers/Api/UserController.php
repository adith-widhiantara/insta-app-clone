<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Services\UserService;
use Laravolt\Crud\CrudModel;
use Laravolt\Crud\CrudService;

class UserController extends BaseController
{
    public function service(): CrudService
    {
        return new UserService($this->model());
    }

    public function model(): CrudModel
    {
        return new User;
    }
}
