<?php

namespace App\Policies;

use App\Models\PostComment;
use App\Models\User;

class PostCommentPolicy
{
    public function delete(User $user, PostComment $comment): bool
    {
        return $user->hasAnyRole(['owner', 'admin', 'member']) || $comment->author_id === $user->id;
    }
}
