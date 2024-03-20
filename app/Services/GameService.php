<?php

namespace App\Services;

use App\Models\UserGame;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection ;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Fluent;
use MarcReichel\IGDBLaravel\Enums\Image\Size;
use MarcReichel\IGDBLaravel\Models\Artwork;
use MarcReichel\IGDBLaravel\Models\Game as IGDBGame;
use MarcReichel\IGDBLaravel\Models\GameVideo;
use MarcReichel\IGDBLaravel\Models\InvolvedCompany;

class GameService
{
    public function search(string $gameName): Collection
    {
        try {
            $igdbGames = IGDBGame::fuzzySearch(['name'], $gameName)
                ->select(['id', 'name', 'platforms', 'cover', 'first_release_date'])
                ->with(['cover', 'platforms' => ['abbreviation' , 'id']])
                ->orderBy('total_rating', 'desc')
                ->limit(5)
                ->get();
            return $igdbGames->map(function ($game) {
                $game->coverImg = $game->cover->getUrl(Size::ORIGINAL) ?? 'https://via.placeholder.com/264x352?text=No+Cover';
                $game->year_release_date = $game->first_release_date ? Carbon::parse($game->first_release_date)->format('Y') : '';
                return $game;
            });

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return collect([]);
        }
    }

    public function searchGame($gameName, $orderBy, $asc): Collection
    {
        try {
            $igdbGames = IGDBGame::fuzzySearch(['name'], $gameName)
                ->select([ 'id', 'name', 'platforms', 'cover', 'first_release_date'])
                ->with(['cover', 'platforms' => ['abbreviation' , 'id']])
                ->orderBy($orderBy ?? 'total_rating', $asc ? 'asc' : 'desc')
                ->all();
            return $igdbGames->map(function ($game) {
                $game->total_rating = 'N/A';
                $game->coverImg = $game->cover ? $game->cover->getUrl(Size::ORIGINAL) : 'https://via.placeholder.com/264x352?text=No+Cover';
                $game->release_date = $game->first_release_date ? Carbon::parse($game->first_release_date)->format('d M Y') : '';
                return $game;
            });
        } catch (Exception $e) {
            return collect([]);
        }

    }

    public function find($id): IGDBGame
    {
        $game = IGDBGame::select(['name', 'summary', 'first_release_date', 'cover', 'platforms', 'involved_companies', 'screenshots', 'artworks', 'genres'])
            ->where('id', '=', $id)
            ->with(['cover', 'platforms' => ['abbreviation', 'id'], 'genres' => ['name'], 'screenshots', 'artworks'])
            ->first();

        if (!$game) {
            return collect([]);
        }
        $usersGame = $this->getUsersGame($game);
        $images = $this->getImagesOfGame($game);
        $game->total_rating = $usersGame['totalRating'];
        $game->count_rating = $usersGame['countRating'];
        $game->reviews = $usersGame['reviews'];
        $game->total_playing = $usersGame['totalPlaying'];
        $game->total_wishlisted = $usersGame['totalWishlisted'];
        $game->total_played = $usersGame['totalPlayed'];
        $game->involved_companies = $this->getCompaniesOfGame($game->involved_companies ?? []);
        $game->background = $images ? $images->first() : 'https://via.placeholder.com/1920x1080?text=No+Background';
        $game->coverImg = $game->cover ? $game->cover->getUrl(Size::ORIGINAL) : 'https://via.placeholder.com/264x352?text=No+Cover';
        $game->release_date = $game->first_release_date ? Carbon::parse($game->first_release_date)->format('d M Y') : '';
        return $game;
    }



    private function getImagesOfGame($game): Collection
    {
        $images = collect();
        $screenshots = $game->screenshots;
        if ($screenshots->isNotEmpty()) {
            $images = $images->concat($screenshots->map(function ($screenshot) {
                return $screenshot->getUrl(Size::ORIGINAL);
            }));
        }
        $artworks = new Fluent($game->artworks);
        if ($artworks->isNotEmpty()) {
            $images = $images->concat($artworks->map(function ($artwork) {
                return  Artwork::find($artwork->id)->getUrl(Size::ORIGINAL);
            }));
        }
        return $images;
    }

    private function getCompaniesOfGame($involvedCompanies): Collection
    {
        return collect($involvedCompanies)->map(function ($company) {
            return InvolvedCompany::where('id', $company)->with(['company'])->first();
        });
    }

    private function getUsersGame($game)
    {
        $totalRating = 0;
        $totalPlaying = 0;
        $countRating = 0;
        $totalWishlisted = 0;
        $totalPlayed = 0;

        $userGames = UserGame::where('igdb_id', $game->id)->get();
        $ratings = $userGames->pluck('rating');

        $reviews = $userGames->map(function ($userGame) {
            return [
                'id' => $userGame->id,
                'review' => $userGame->review,
                'user' => $userGame->user,
                'created_at' =>  Carbon::parse($userGame->created_at)->format('d M Y')
            ];
        });
        if ($ratings->isNotEmpty()) {
            $countRating = $ratings->count();
            $totalRating = $ratings->sum() / $ratings->count();
        }
        $status = $userGames->pluck('status');

        if ($status->isNotEmpty()) {
            $totalPlaying = $status->filter(function ($value) {
                return $value === 'playing';
            })->count();
            $totalWishlisted = $status->filter(function ($value) {
                return $value === 'wishlisted';
            })->count();
            $totalPlayed = $status->filter(function ($value) {
                return $value === 'played' || $value === 'completed' || $value === 'dropped';
            })->count();
        }

        return [
            'reviews' => $reviews,
            'countRating' => $countRating,
            'totalRating' => $totalRating,
            'totalWishlisted' => $totalWishlisted,
            'totalPlaying' => $totalPlaying,
            'totalPlayed' => $totalPlayed
        ];
    }
}
