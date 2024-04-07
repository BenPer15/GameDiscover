<?php

namespace App\Services;

use App\Models\Game;
use App\Models\UserGameInteraction;
use Error;
use Exception;
use Illuminate\Support\Collection ;
use Illuminate\Support\Facades\Log;
use MarcReichel\IGDBLaravel\Models\Artwork;
use MarcReichel\IGDBLaravel\Models\InvolvedCompany;


use App\Traits\ManagesTwitchStreams;
use App\Traits\ManagesGameImages;
use romanzipp\Twitch\Twitch;

class GameService
{
    use ManagesGameImages;
    use ManagesTwitchStreams;

    public const HD = 720;
    public const FULL_HD = 1280;


    public function search(string $gameName): Collection
    {
        try {
            if($gameName === null) {
                throw new Exception('Game name is required');
            }
            $igdbGames = Game::where('name', $gameName)
                ->orWhere('name', 'ilike', '%' . $gameName . '%')
                ->where('platforms', '!=', null)
                ->select(['id', 'name', 'platforms', 'cover', 'first_release_date'])
                ->with(['cover', 'platforms' => ['abbreviation' , 'id']])
                ->orderBy('total_rating', 'desc')
                ->limit(5)
                ->get();
            return $igdbGames->map(function ($game) {
                $game->cover_url = $this->getImageUrl($game->cover);
                $game->year_release_date = formatDate($game->first_release_date, 'Y');
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
            $game = Game::where('name', $gameName)
               ->orWhere('name', 'ilike', '%' . $gameName . '%')
               ->where('platforms', '!=', null)
               ->select([ 'id', 'name', 'platforms', 'cover', 'first_release_date'])
               ->with(['cover', 'platforms' => ['abbreviation' , 'id']])
               ->orderBy($orderBy ?? 'total_rating', $asc ? 'asc' : 'desc')
               ->all();
            return $game->map(function ($game) {
                $game->cover_url = $this->getImageUrl($game->cover);
                $game->release_date = formatDate($game->first_release_date);
                return $game;
            });
        } catch (Exception $e) {
            return collect([]);
        }
    }

    public function find($id): Game
    {
        $game = Game::select(['name', 'summary', 'storyline', 'first_release_date', 'cover', 'platforms', 'involved_companies', 'genres', 'game_modes', 'age_ratings'])
            ->where('id', '=', $id)
            ->with(['cover', 'platforms' => ['abbreviation', 'id'], 'genres' => ['name'], 'game_modes', 'age_ratings'])
            ->first();
        if (!$game) {
            return collect([]);
        }
        $game->matureContent = $this->getMatureContent($game->age_ratings);
        $game->gameUserInteractions = $this->getGameUserInteractions($game->id);
        $game->involved_companies = $this->getCompaniesOfGame($game->involved_companies);
        $game->cover_url = $this->getImageUrl($game->cover);
        $game->year_release_date = formatDate($game->first_release_date, 'Y');
        $game->release_date = formatDate($game->first_release_date);
        return $game;
    }

    private function getMatureContent($ageRatings): ?array
    {
        if ($ageRatings === null) {
            return null;
        }
        $listOfMatureRating = [4,5,11,12,16,17,21,22,26,32,33,37,38];
        $matureRating = $ageRatings->first(function ($ageRating) use ($listOfMatureRating) {
            return in_array($ageRating['rating'], $listOfMatureRating);
        });
        return $matureRating ? [
                'rating' => $matureRating['rating'],
                'synopsis' => $matureRating['synopsis']
            ] : null;

    }

    public function getBackgroundOfGame($id)
    {
        $game = Game::select(['screenshots',  'artworks'])
                   ->where('id', '=', $id)
                   ->with(['screenshots', 'artworks'])
                   ->first();
        $screenshots = $game->screenshots->toArray();

        $images = collect($game->artworks ? array_merge($screenshots, $game->artworks) : $screenshots)->map(function ($image) {
            $id = $image['image_id'];
            return [
               'id' => $id,
                'height' => $image['height'],
                'hd' => $image['height'] > self::HD && $image['height'] < self::FULL_HD,
                'fullHd' => $image['height'] >= self::FULL_HD,
            ];
        });
        $fullHdImageUrl = $this->getRandomImageUrl($images, self::FULL_HD);
        if ($fullHdImageUrl !== null) {
            return $fullHdImageUrl;
        }
        $hdImageUrl = $this->getRandomImageUrl($images, self::HD);
        if ($hdImageUrl !== null) {
            return $hdImageUrl;
        }
        $otherImageUrl = $this->getRandomImageUrl($images);
        return $otherImageUrl;
    }


    public function getMedias($id): Collection
    {
        $game = Game::select(['screenshots', 'videos', 'artworks'])
                    ->where('id', '=', $id)
                    ->with(['screenshots', 'videos', 'artworks'])
                    ->first();
        $medias = collect($game->videos);
        $medias = $medias->concat($game->screenshots->map(function ($screenshot) {
            return [
                'url' => $this->getImageUrl($screenshot),
                'type' => 'screenshot',
                'hd' => $screenshot->height > self::HD && $screenshot->height < self::FULL_HD,
                'fullHd' => $screenshot->height >= self::FULL_HD,
            ];
        }));
        if (!empty($game->artworks)) {
            $artworkMedias = collect($game->artworks)->map(function ($artwork) {
                $artworkObj = Artwork::find($artwork['id']);
                return [
                    'url' => $this->getImageUrl($artworkObj),
                    'type' => 'artwork',
                    'hd' => $artwork['height'] > self::HD && $artwork['height'] < self::FULL_HD,
                    'fullHd' => $artwork['height'] >= self::FULL_HD,
                ];
            });
            $medias = $medias->concat($artworkMedias);
        }
        return $medias;
    }

    private function getCompaniesOfGame($involvedCompanies): Collection
    {
        if($involvedCompanies === null) {
            return collect([]);
        }
        return InvolvedCompany::whereIn('id', $involvedCompanies)->with(['company'])->get();
    }

    private function getImageUrl($image): string
    {
        if($image === null) {
            return 'https://via.placeholder.com/264x352?text=No+Cover';
        }
        $id = $image->getAttribute('image_id');
        if ($id === null) {
            throw new Error('Property [image_id] is missing from the response. Make sure you specify `image_id` inside the fields attribute.');
        }

        return static::IMAGE_BASE_PATH . "/t_original/$id.jpg";
    }


    private function getGameUserInteractions($igdb_id): array
    {
        $userGameInteractionsData = UserGameInteraction::where('igdb_id', $igdb_id)->get();
        $statusCounts = $userGameInteractionsData->groupBy('status')
                ->map->count();
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

    public function getMatureContentForGame($id)
    {
        $game = Game::select(['name', 'cover', 'age_ratings'])
            ->where('id', '=', (int)$id)
            ->with(['cover', 'age_ratings'])
            ->first();
        if (!$game) {
            return collect([]);
        }
        $game->matureContent = $this->getMatureContent($game->age_ratings);
        $game->cover_url = $this->getImageUrl($game->cover);
        return $game;
    }

    public function getStream($gameId)
    {
        $lang = in_array($lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2), $acceptLang = ['fr', 'it', 'en', 'es']) ? $lang : 'en';
        $twitch = new Twitch();

        if ($twitchGame = $twitch->getGames(['igdb_id' => $gameId])->shift()) {
            return $twitch->getStreams(['game_id' => $twitchGame->id, 'first' => 1, 'language' => $lang])->shift();
        }
        return null;
    }
}
