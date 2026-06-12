<?php

namespace App\Providers;

use App\Models\Board;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\User;
use App\Policies\BoardPolicy;
use App\Policies\PostCommentPolicy;
use App\Policies\PostPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Board::class => BoardPolicy::class,
        Post::class => PostPolicy::class,
        PostComment::class => PostCommentPolicy::class,
    ];

    public function boot(): void
    {
        Gate::define('manage-feedback', fn (User $user) => $user->hasAnyRole(['owner', 'admin', 'member']));
        Gate::define('view-board', fn (User $user, Board $board) => true);
        Gate::define('update-post', fn (User $user, Post $post) => $user->hasAnyRole(['owner', 'admin', 'member']) || $post->author_id === $user->id);
    }
}
