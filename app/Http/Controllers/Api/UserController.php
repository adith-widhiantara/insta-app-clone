<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Laravolt\Crud\CrudModel;
use App\Http\Controllers\BaseController;

class UserController extends BaseController
{
    public function model(): CrudModel
    {
        return new User();
    }
}
