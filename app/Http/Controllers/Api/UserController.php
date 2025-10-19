<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Laravolt\Crud\CrudModel;

class UserController extends BaseController
{
    public function model(): CrudModel
    {
        return new User;
    }
}
