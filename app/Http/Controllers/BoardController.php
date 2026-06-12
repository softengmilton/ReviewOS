<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Http\Requests\StoreBoardRequest;
use App\Http\Requests\UpdateBoardRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class BoardController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', Board::class);

        return Inertia::render('Boards/Index', [
            'boards' => Board::withCount('posts')->orderBy('sort_order')->paginate(25),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Board::class);

        return Inertia::render('Boards/Create');
    }

    public function store(StoreBoardRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $board = Board::create([
            ...$validated,
            'slug' => $this->uniqueSlug($validated['name']),
        ]);

        return redirect()->route('boards.show', $board)->with('success', 'Board created.');
    }

    public function show(Board $board): Response
    {
        $this->authorize('view', $board);

        return Inertia::render('Boards/Show', [
            'board' => $board,
            'posts' => $board->posts()->with(['status', 'author'])->latest()->paginate(25),
        ]);
    }

    public function edit(Board $board): Response
    {
        $this->authorize('update', $board);

        return Inertia::render('Boards/Edit', ['board' => $board]);
    }

    public function update(UpdateBoardRequest $request, Board $board): RedirectResponse
    {
        $validated = $request->validated();

        $board->update([...$validated, 'slug' => $this->uniqueSlug($validated['name'], $board)]);

        return redirect()->route('boards.show', $board)->with('success', 'Board updated.');
    }

    public function destroy(Board $board): RedirectResponse
    {
        $this->authorize('delete', $board);

        $board->delete();

        return redirect()->route('boards.index')->with('success', 'Board deleted.');
    }

    private function uniqueSlug(string $name, ?Board $ignore = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $suffix = 2;

        while (Board::query()
            ->when($ignore, fn ($query) => $query->whereKeyNot($ignore->getKey()))
            ->where('slug', $slug)
            ->exists()
        ) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
