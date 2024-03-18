<?php

namespace App\Services;

use App\Models\UserGame;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection ;
use Illuminate\Support\Facades\Log;
use MarcReichel\IGDBLaravel\Enums\Image\Size;
use MarcReichel\IGDBLaravel\Models\Artwork;
use MarcReichel\IGDBLaravel\Models\Game as IGDBGame;
use MarcReichel\IGDBLaravel\Models\InvolvedCompany;

class GameService
{
    private $withSearch = ['cover', 'platforms' => ['abbreviation' , 'id']];

    public function search(string $gameName): Collection
    {
        try {
            $igdbGames = IGDBGame::fuzzySearch(['name'], $gameName)
                ->with($this->withSearch)
                ->orderBy('total_rating', 'desc')
                ->limit(5)
                ->get();
            return $igdbGames->map(function ($game) {
                return [
                    'id' => $game->id,
                    'name' => $game->name,
                    'platforms' => $game->platforms,
                    'cover' => $game->cover->getUrl(Size::ORIGINAL) ?? 'https://via.placeholder.com/264x352?text=No+Cover',
                    'year_release_date' => $game->first_release_date ? Carbon::parse($game->first_release_date)->format('Y') : '',
                ];
            });

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return collect([]);
        }

    }


    public function searchGame($gameName, $orderBy, $asc)
    {
        try {
            $igdbGames = IGDBGame::fuzzySearch(['name'], $gameName)
                ->with($this->withSearch)
                ->orderBy($orderBy ?? 'total_rating', $asc ? 'asc' : 'desc')
                ->all();
            return $igdbGames->map(function ($game) {
                return [
                    'id' => $game->id,
                    'name' => $game->name,
                    'platforms' => $game->platforms,
                    'total_rating' => 'N/A',
                    'cover' => $game->cover ? $game->cover->getUrl(Size::ORIGINAL) : 'https://via.placeholder.com/264x352?text=No+Cover',
                    'release_date' => $game->first_release_date ? Carbon::parse($game->first_release_date)->format('d M Y') : '',
                ];
            });
        } catch (Exception $e) {
            return collect([]);
        }

    }

    public function find($id)
    {
        $igdbGames = IGDBGame::where('id', '=', $id)->with(['cover', 'platforms' => ['abbreviation', 'id'], 'genres' => ['name'], 'screenshots', 'artworks'])->get();
        return $igdbGames->map(function ($game) {
            // $reviews = UserGame::where('igbd_id', $game->id);
            // $nbRating = $reviews->count();
            $gameData = $game instanceof IGDBGame ? $game->toArray() : (array) $game;
            $images = $this->getImagesOfGame($game);
            return array_merge($gameData, [
                'total_rating' => 'N/A',
                'nb_rating' => 0,
                'involved_companies' => $this->getCompaniesOfGame($game->involved_companies ?? []),
                'background' => $this->getBackgroundOfGame($images),
                'images' => $images,
                'cover' => $game->cover ? $game->cover->getUrl(Size::ORIGINAL) : 'https://via.placeholder.com/264x352?text=No+Cover',
                'release_date' => $game->first_release_date ? Carbon::parse($game->first_release_date)->format('d M Y') : '',
            ]);
        });

    }

    private function getBackgroundOfGame($images)
    {
        if ($images && count($images) > 0) {
            return $images[rand(0, count($images) - 1)];
        }
        return 'https://via.placeholder.com/1920x1080?text=No+Background';
    }

    private function getImagesOfGame($game)
    {
        $images = [];
        $screenshots = $game->screenshots;
        if ($screenshots && count($screenshots) > 0) {
            foreach ($screenshots as $screenshot) {
                $images[] = $screenshot->getUrl(Size::ORIGINAL);
            }
        }
        $artworks = $game->artworks;
        if ($artworks && count($artworks) > 0) {
            foreach ($artworks as $artwork) {
                $images[] = Artwork::find($artwork['id'])->getUrl(Size::ORIGINAL);
            }
        }
        return $images;

    }

    private function getCompaniesOfGame($involvedCompanies)
    {
        return collect($involvedCompanies)->map(function ($company) {
            return InvolvedCompany::where('id', $company)->with(['company'])->first();
        });
    }
}
