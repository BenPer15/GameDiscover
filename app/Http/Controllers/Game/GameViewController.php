<?php

namespace App\Http\Controllers\Game;

use Illuminate\Http\Request;
use Inertia\Inertia;

class GameViewController extends GameBaseController
{
    public function show($id)
    {
        return Inertia::render('Games/Show', ['game_id' => $id]);
    }

    public function searchAll(Request $request)
    {
        $query = $request->input('q');
        $sort = $request->input('sort');
        $asc = $request->input('asc');

        $games = $this->gameService->searchGame($query, $sort, $asc);
        return Inertia::render('Games/Index', compact('games', 'query', 'sort', 'asc'));
    }

    public function advancedSearch()
    {
    }

    public function matureContent()
    {
        $query = request()->input('q');
        return Inertia::render('MatureForm', ['gameId' => $query ]);
    }
}
