<?php

namespace App\Services;

class UserService extends Service
{
    public function appendQuery($query)
    {
        $query = parent::appendQuery($query);

        $query
            ->where('id', '!=', auth()->id())
            ->with(['followers']);

        return $query;
    }
}
