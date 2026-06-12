<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostVoteRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class PostVoteController extends Controller
{
    public function store(StorePostVoteRequest $request, Post $post): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();

        $vote = $post->votes()->updateOrCreate(
            [
                'voter_type' => $request->user()::class,
                'voter_id' => $request->user()->id,
            ],
            ['direction' => $validated['direction'] ?? 'up']
        );

        $post->forceFill([
            'upvotes_count' => $post->votes()->where('direction', 'up')->count(),
            'downvotes_count' => $post->votes()->where('direction', 'down')->count(),
        ])->saveQuietly();

        $payload = [
            'vote' => $vote,
            'upvotes_count' => $post->upvotes_count,
            'downvotes_count' => $post->downvotes_count,
        ];

        if ($request->expectsJson()) {
            return response()->json($payload);
        }

        return back()->with('success', 'Vote recorded.');
    }
}
