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

        $isCommentOwner = $comment->user_id === $userId;
        $isPostOwner = $comment->post->user_id === $userId;

        if (! $isCommentOwner && ! $isPostOwner) {
            // Jika tidak memiliki izin, lempar ValidationException
            throw ValidationException::withMessages(
                ['user_id' => 'You cannot delete this comment! You must be the comment owner or the post owner.']
            );
        }

        return parent::delete($model);
    }
}
