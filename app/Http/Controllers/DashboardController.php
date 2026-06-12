<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostVote;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Dashboard', [
            'metrics' => [
                'boards' => Board::count(),
                'posts' => Post::count(),
                'votes' => PostVote::count(),
                'comments' => PostComment::count(),
            ],
            'recentPosts' => Post::with(['board', 'status'])
                ->latest()
                ->limit(8)
                ->get(),
        ]);
    }
}
