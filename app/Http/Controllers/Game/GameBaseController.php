<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Services\GameService;
use Illuminate\Contracts\Auth\Guard;

class GameBaseController extends Controller
{
    protected $gameService;
    protected $auth;

    public function __construct(GameService $gameService, Guard $auth)
    {
        $this->gameService = $gameService;
        $this->auth = $auth;
    }
}
