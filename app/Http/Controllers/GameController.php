<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchGameRequest;
use App\Models\Game;
use MarcReichel\IGDBLaravel\Models\Game as IGDBGame;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::all();
        return view('game/index', compact('games'));
    }

    public function searchGame(SearchGameRequest $request)
    {
        $searchTerm = $request->input('search');
        $games = Game::where('title', 'like', '%' . $searchTerm . '%')->get();

        if ($games->isEmpty()) {
            // Game not found in local database, search in IGDB API
            $igdbGames = $this->searchGameInIGDB($searchTerm);
            $games = $igdbGames->map(function ($game) {
                return [
                    'title' => $game->name,
                    'cover' => $game->cover->url,
                    'summary' => $game->summary,
                ];
            });
            return view('game/index', compact('games'));
        }
        return view('game/index', compact('games'));
    }

    private function searchGameInIGDB($searchTerm)
    {
        return IGDBGame::search($searchTerm)->get();
    }
}
