<?php

namespace App\Http\Controllers\Game;

use Illuminate\Http\Request;

class GameMediaController extends GameBaseController
{
    public function getTwitchStream($id)
    {
        // $game = $this->gameService->find((int)$id);
        // $twitchStreams = $this->gameService->getTwitchStreams($game->name);
        // return response()->json($twitchStreams);
    }
}
