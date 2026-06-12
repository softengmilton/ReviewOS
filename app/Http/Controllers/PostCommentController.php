<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostComment;
use App\Http\Requests\StorePostCommentRequest;
use Illuminate\Http\RedirectResponse;

class PostCommentController extends Controller
{
    public function store(StorePostCommentRequest $request, Post $post): RedirectResponse
    {
        $validated = $request->validated();

        $comment = new PostComment($validated);
        $comment->author()->associate($request->user());
        $comment->post()->associate($post);
        $comment->save();

        $post->increment('comments_count');

        return back()->with('success', 'Comment added.');
    }

    public function destroy(PostComment $comment): RedirectResponse
    {
        $this->authorize('delete', $comment);

        $comment->delete();
        $comment->post()->decrement('comments_count');

        return back()->with('success', 'Comment deleted.');
    }
}
