<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends BaseModel
{
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
