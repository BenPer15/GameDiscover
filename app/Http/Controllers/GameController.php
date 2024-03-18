<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Services\GameService;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Fluent;
use Inertia\Inertia;
use MarcReichel\IGDBLaravel\Models\Game as IGDBGame;

class GameController extends Controller
{
    private $gameService;
    protected $auth;

    public function __construct(GameService $gameService, Guard $auth)
    {
        $this->gameService = $gameService;
        $this->auth = $auth;

    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $games = $this->gameService->search($query);
        return response()->json($games);
    }

    public function searchAll(Request $request)
    {
        $query = $request->input('q');
        // $limit = $request->input('limit');
        // $offset = $request->input('offset');
        $sort = $request->input('sort');
        $asc = $request->input('asc');

        $games = $this->gameService->searchGame($query, $sort, $asc);
        return Inertia::render('Games/Index', compact('games', 'query', 'sort', 'asc'));
    }

    public function advancedSearch(Request $request)
    {
        $searchTerm = $request->input('q');
        // $games = $this->gameService->searchGame($searchTerm);
        // return response()->json($games);
    }

    public function show($id)
    {
        $games = $this->gameService->find((int)$id);
        $game = new Fluent($games->first());
        $user = $this->auth->user();
        if ($user && $game) {
            $gameReviewedByUser = $user->games->where('igdb_id', $game->id)->first();
            $game->total_rating = $gameReviewedByUser ? $gameReviewedByUser->rating : $game->total_rating;
            $game->review = $gameReviewedByUser ? $gameReviewedByUser->review : '';
            $game->is_favorite = $gameReviewedByUser ? $gameReviewedByUser->is_favorite : false;
            $game->is_wishlisted = $gameReviewedByUser ? $gameReviewedByUser->is_wishlisted : false;
            $game->is_finished = $gameReviewedByUser ? $gameReviewedByUser->is_finished : false;
        }
        return Inertia::render('Games/Show', ['game' => $game]);
    }


}
