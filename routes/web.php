<?php

use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostVoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Webhooks\StripeWebhookController;
use App\Models\Board;
use App\Models\Post;
use App\Models\PostComment;
use Illuminate\Support\Facades\Route;

Route::bind('board', function (string $value): Board {
    $organizationIds = auth()->user()?->organizations()->pluck('organizations.id') ?? collect();

    return Board::withoutGlobalScopes()
        ->whereKey($value)
        ->whereIn('organization_id', $organizationIds)
        ->firstOrFail();
});

Route::bind('post', function (string $value): Post {
    $organizationIds = auth()->user()?->organizations()->pluck('organizations.id') ?? collect();

    return Post::withoutGlobalScopes()
        ->whereKey($value)
        ->whereIn('organization_id', $organizationIds)
        ->firstOrFail();
});

Route::bind('comment', function (string $value): PostComment {
    $organizationIds = auth()->user()?->organizations()->pluck('organizations.id') ?? collect();

    return PostComment::withoutGlobalScopes()
        ->whereKey($value)
        ->whereIn('organization_id', $organizationIds)
        ->firstOrFail();
});

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])->name('oauth.redirect');
    Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])->name('oauth.callback');
});

Route::middleware(['auth'])->group(function (): void {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::resource('boards', BoardController::class);
    Route::resource('boards.posts', PostController::class)->shallow();
    Route::post('/posts/{post}/vote', [PostVoteController::class, 'store'])->name('posts.vote');
    Route::post('/posts/{post}/comments', [PostCommentController::class, 'store'])->name('posts.comments.store');
    Route::delete('/comments/{comment}', [PostCommentController::class, 'destroy'])->name('comments.destroy');

    Route::get('/settings/billing', [BillingController::class, 'index'])->name('billing.index');
    Route::post('/settings/billing/checkout', [BillingController::class, 'checkout'])->name('billing.checkout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/webhooks/stripe', [StripeWebhookController::class, 'handle'])->name('webhooks.stripe');

require __DIR__.'/auth.php';
