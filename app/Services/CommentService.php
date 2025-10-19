<?php

namespace App\Services;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CommentService extends Service
{
    /**
     * @throws ValidationException
     */
    public function delete(mixed $model): ?bool
    {
        /** @var Comment $comment */
        $comment = $model;

        $comment->load(['post']);

        $userId = Auth::id();

        if ($comment->user_id !== $userId || $comment->post->user_id !== $userId) {
            throw ValidationException::withMessages(
                ['user_id' => 'You cannot delete this comment!']
            );
        }

        return parent::delete($model);
    }
}
