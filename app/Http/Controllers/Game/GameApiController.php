<?php

namespace App\Http\Controllers\Game;

use Illuminate\Http\Request;

class GameApiController extends GameBaseController
{
    public function search(Request $request)
    {
        $query = $request->input('q');
        $games = $this->gameService->search($query);
        return response()->json($games);
    }

    public function getGame($id)
    {
        $game = $this->gameService->find((int)$id, );
        return response()->json($game);
    }

    public function getMedias($id)
    {
        $medias = $this->gameService->getMedias((int)$id);
        return response()->json($medias);
    }

    public function getBackgroundImage($id)
    {
        $background = $this->gameService->getBackgroundOfGame((int)$id);
        return response()->json($background);
    }

    public function getMatureContent()
    {
        $gameId = request()->input('gameId');
        $matureContent = $this->gameService->getMatureContentForGame($gameId);
        return response()->json($matureContent);
    }
}
