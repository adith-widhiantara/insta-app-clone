<?php

namespace App\Http\Requests;

use Laravolt\Crud\CrudRequest;

abstract class Request extends CrudRequest
{
    protected string $required = 'required';

    protected string $string = 'string';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
