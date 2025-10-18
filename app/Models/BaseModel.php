<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravolt\Crud\CrudModel;

abstract class BaseModel extends CrudModel
{
    use HasFactory;

    protected $guarded = [];
}
