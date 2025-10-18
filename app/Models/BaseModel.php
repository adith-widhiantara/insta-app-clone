<?php

namespace App\Models;

use Laravolt\Crud\CrudModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

abstract class BaseModel extends CrudModel
{
    use HasFactory;
}
