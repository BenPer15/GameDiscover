<?php

namespace App\Http\Controllers\Game;

use Illuminate\Http\Request;

class GameMediaController extends GameBaseController
{
    public function getTwitchStream($id)
    {
        $twitchStreams = $this->gameService->getStream($id);
        return response()->json($twitchStreams);
    }
}
