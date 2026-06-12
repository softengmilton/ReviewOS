<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['owner', 'admin', 'member', 'viewer']);
    }

    public function view(User $user, Post $post): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['owner', 'admin', 'member']);
    }

    public function update(User $user, Post $post): bool
    {
        return $user->hasAnyRole(['owner', 'admin', 'member']) || $post->author_id === $user->id;
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->hasAnyRole(['owner', 'admin', 'member']) || $post->author_id === $user->id;
    }

    public function vote(User $user, Post $post): bool
    {
        return $user->hasAnyRole(['owner', 'admin', 'member', 'viewer']);
    }

    public function comment(User $user, Post $post): bool
    {
        return $user->hasAnyRole(['owner', 'admin', 'member', 'viewer']);
    }
}
