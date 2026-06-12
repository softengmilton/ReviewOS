<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\PostStatus;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    public function index(Board $board): RedirectResponse
    {
        return redirect()->route('boards.show', $board);
    }

    public function create(Board $board): Response
    {
        $this->authorize('create', Post::class);

        return Inertia::render('Posts/Create', [
            'board' => $board,
            'statuses' => PostStatus::orderBy('sort_order')->get(),
        ]);
    }

    public function store(StorePostRequest $request, Board $board): RedirectResponse
    {
        $validated = $request->validated();

        $post = new Post($validated);
        $post->status_id = PostStatus::orderBy('sort_order')->value('id');
        $post->author()->associate($request->user());
        $post->board()->associate($board);
        $post->save();

        $post->subscribers()->create([
            'subscriber_type' => $request->user()::class,
            'subscriber_id' => $request->user()->id,
            'notify_status_change' => true,
            'notify_comment' => true,
        ]);

        return redirect()->route('posts.show', $post)->with('success', 'Post submitted.');
    }

    public function show(Post $post): Response
    {
        $this->authorize('view', $post);

        return Inertia::render('Posts/Show', [
            'post' => $post->load(['board', 'status', 'author', 'comments.author']),
            'statuses' => PostStatus::orderBy('sort_order')->get(),
        ]);
    }

    public function edit(Post $post): Response
    {
        $this->authorize('update', $post);

        return Inertia::render('Posts/Edit', [
            'post' => $post,
            'statuses' => PostStatus::orderBy('sort_order')->get(),
        ]);
    }

    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        $validated = $request->validated();

        $post->update($validated);

        return back()->with('success', 'Post updated.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->authorize('delete', $post);

        $board = $post->board;
        $post->delete();

        return redirect()->route('boards.show', $board)->with('success', 'Post deleted.');
    }
}
