<?php

use App\Http\Controllers\PostVoteController;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

Route::bind('post', function (string $value): Post {
    $organizationIds = auth()->user()?->organizations()->pluck('organizations.id') ?? collect();

    return Post::withoutGlobalScopes()
        ->whereKey($value)
        ->whereIn('organization_id', $organizationIds)
        ->firstOrFail();
});

Route::prefix('v1')->middleware('auth:sanctum')->group(function (): void {
    Route::post('/posts/{post}/vote', [PostVoteController::class, 'store']);
});
