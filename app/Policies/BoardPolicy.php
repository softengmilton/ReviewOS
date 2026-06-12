<?php

namespace App\Policies;

use App\Models\Board;
use App\Models\User;

class BoardPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['owner', 'admin', 'member', 'viewer']);
    }

    public function view(User $user, Board $board): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['owner', 'admin', 'member']);
    }

    public function update(User $user, Board $board): bool
    {
        return $user->hasAnyRole(['owner', 'admin', 'member']);
    }

    public function delete(User $user, Board $board): bool
    {
        return $user->hasAnyRole(['owner', 'admin']);
    }
}
