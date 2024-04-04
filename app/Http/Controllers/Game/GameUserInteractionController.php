<?php

namespace App\Http\Controllers\Game;

use App\Http\Requests\UserGameInteractionRequest;
use App\Models\UserGameInteraction;
use App\Services\GameService;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class GameUserInteractionController extends GameBaseController
{
    protected $gameService;
    protected $auth;

    public function __construct(GameService $gameService, Guard $auth)
    {
        $this->gameService = $gameService;
        $this->auth = $auth;

    }

    public function storeUserGameInteraction(UserGameInteractionRequest $request)
    {
        $validated = $request->validated();
        $user = $this->auth->user();
        $userGameInteraction = UserGameInteraction::create(
            [
                'user_id' => $user->id,
                'igdb_id' => $validated['igdb_id'],
                'status' => $validated['status'] ?? null,
                'is_favorite' => $validated['is_favorite'] ?? false,
            ]
        );
        $data = $this->formatUserInteractionsData($userGameInteraction->igdb_id);
        return response()->json($data);
    }

    public function updateUserGameInteraction(UserGameInteractionRequest $request, $id)
    {
        $userGameInteraction = UserGameInteraction::findOrFail($id);
        $validated = $request->validated();
        $userGameInteraction->update(
            [
                'status' => $validated['status'],
                'is_favorite' => $validated['is_favorite'] ?? $userGameInteraction->is_favorite,
            ]
        );
        $data = $this->formatUserInteractionsData($userGameInteraction->igdb_id);
        return response()->json($data);
    }

    private function formatUserInteractionsData($id)
    {
        $userGameInteractionsData = UserGameInteraction::where('igdb_id', $id)->get();
        $statusCounts = $userGameInteractionsData->groupBy('status')->map->count();
        $totalPlaying = $statusCounts->get('playing', 0);
        $totalWishlisted = $statusCounts->get('wishlisted', 0);
        $totalPlayed = collect(['played', 'completed', 'dropped'])
            ->sum(function ($status) use ($statusCounts) {
                return $statusCounts->get($status, 0);
            });
        $gameInteractionByUser = $userGameInteractionsData->firstWhere('user_id', auth()->id());

        return [
            'totalPlaying' => $totalPlaying,
            'totalWishlisted' => $totalWishlisted,
            'totalPlayed' => $totalPlayed,
            'currentUser' => $gameInteractionByUser
        ];
    }


    public function getUserInteractions($id)
    {
        $data = $this->formatUserInteractionsData($id);
        return response()->json($data);
    }
}
