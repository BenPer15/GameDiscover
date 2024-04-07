<?php

namespace App\Traits;

use romanzipp\Twitch\Twitch;

trait ManagesTwitchStreams
{
    protected function getStream($gameId)
    {

        $lang = in_array($lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2), $acceptLang = ['fr', 'it', 'en', 'es']) ? $lang : 'en';
        $twitch = new Twitch();

        if ($twitchGame = $twitch->getGames(['igdb_id' => $gameId])->shift()) {
            return $twitch->getStreams(['game_id' => $twitchGame->id, 'first' => 1, 'language' => $lang])->shift();
        }
        return null;
    }
}
