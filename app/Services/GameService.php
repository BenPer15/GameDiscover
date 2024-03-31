<?php

namespace App\Services;

use App\Models\Review;
use App\Models\UserGameInteraction;
use Carbon\Carbon;
use Error;
use Exception;
use Illuminate\Support\Collection ;
use Illuminate\Support\Facades\Log;
use MarcReichel\IGDBLaravel\Enums\Image\Size;
use MarcReichel\IGDBLaravel\Models\Artwork;
use MarcReichel\IGDBLaravel\Models\Game as IGDBGame;
use MarcReichel\IGDBLaravel\Models\InvolvedCompany;

use romanzipp\Twitch\Twitch;

class GameService
{
    protected const IMAGE_BASE_PATH = '//images.igdb.com/igdb/image/upload';

    public function search(string $gameName): Collection
    {
        try {
            if($gameName === null) {
                throw new Exception('Game name is required');
            }
            $igdbGames = IGDBGame::where('name', $gameName)
                ->orWhere('name', 'ilike', '%' . $gameName . '%')
                ->where('platforms', '!=', null)
                ->select(['id', 'name', 'platforms', 'cover', 'first_release_date'])
                ->with(['cover', 'platforms' => ['abbreviation' , 'id']])
                ->orderBy('total_rating', 'desc')
                ->limit(5)
                ->get();
            return $igdbGames->map(function ($game) {
                $game->coverImg = $game->cover->getUrl(Size::HD) ?? 'https://via.placeholder.com/264x352?text=No+Cover';
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
            $igdbGames = IGDBGame::where('name', $gameName)
               ->orWhere('name', 'ilike', '%' . $gameName . '%')
               ->where('platforms', '!=', null)
               ->select([ 'id', 'name', 'platforms', 'cover', 'first_release_date'])
               ->with(['cover', 'platforms' => ['abbreviation' , 'id']])
               ->orderBy($orderBy ?? 'total_rating', $asc ? 'asc' : 'desc')
               ->all();
            return $igdbGames->map(function ($game) {
                $game->total_rating = 'N/A';
                $game->coverImg = $game->cover ? $game->cover->getUrl(Size::HD) : 'https://via.placeholder.com/264x352?text=No+Cover';
                $game->release_date = $game->first_release_date ? Carbon::parse($game->first_release_date)->format('d M Y') : '';
                return $game;
            });
        } catch (Exception $e) {
            return collect([]);
        }

    }

    public function find($id): IGDBGame
    {
        $game = IGDBGame::select(['name', 'summary', 'first_release_date', 'cover', 'platforms', 'involved_companies', 'screenshots', 'artworks', 'genres', 'game_modes', 'videos', 'age_ratings' , 'websites'])
            ->where('id', '=', $id)
            ->with(['cover', 'platforms' => ['abbreviation', 'id'], 'genres' => ['name'], 'screenshots', 'artworks','game_modes', 'videos', 'age_ratings', 'websites'])
            ->first();

        if (!$game) {
            return collect([]);
        }

        $reviews = $this->getReviews($game->id);
        $medias = $this->getMedias($game);
        $game->matureContent = $this->getMatureContent($game->age_ratings);
        $game->reviews = $reviews['reviews'] ?? [];
        $game->sentimentsScore = $reviews['sentimentScore'];
        $game->gameUserInteractions = $this->getGameUserInteractions($game->id);
        $game->involved_companies = $this->getCompaniesOfGame($game->involved_companies ?? []);
        $game->medias = $medias;
        $game->background = $this->getBackgroundOfGame($medias);
        $game->stream = $this->getStream($game['id']);
        $game->coverImg = $game->cover ? $game->cover->getUrl(Size::HD) : 'https://via.placeholder.com/264x352?text=No+Cover';
        $game->year_release_date = $game->first_release_date ? Carbon::parse($game->first_release_date)->format('Y') : '';
        $game->release_date = $game->first_release_date ? Carbon::parse($game->first_release_date)->format('d M Y') : '';
        return $game;
    }

    private function getMatureContent($ageRatings)
    {
        $listOfMatureRating = [4,5,11,12,16,17,21,22,26,32,33,37,38];
        $matureRating = null;
        if ($ageRatings && $ageRatings->isNotEmpty()) {
            foreach ($ageRatings as $ageRating) {
                if (in_array($ageRating['rating'], $listOfMatureRating)) {
                    $matureRating = [
                        'rating' => $ageRating['rating'],
                        'synopsis' => $ageRating['synopsis']
                    ];
                    break;
                }
            }
        }
        return $matureRating;
    }

    private function getStream($gameId)
    {

        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        $acceptLang = ['fr', 'it', 'en', 'es'];
        $lang = in_array($lang, $acceptLang) ? $lang : 'en';

        $twitch = new Twitch();
        $twitchGame = $twitch->getGames(['igdb_id' => $gameId])->shift();
        if (!$twitchGame) {
            return null;
        }
        $streams = $twitch->getStreams(['game_id' => $twitchGame->id, 'first' => 1, 'language' => $lang]);
        return $streams->shift();
    }

    private function getRandomImageUrl($images, $condition): ?string
    {
        $filteredImages = $images->filter($condition)->values();
        if ($filteredImages->isNotEmpty()) {
            return $filteredImages[random_int(0, $filteredImages->count() - 1)]['url'];
        }
        return null;
    }

    private function getBackgroundOfGame($images): string | null
    {
        $backgroundFullHD = $this->getRandomImageUrl($images, function ($image) {
            return $image['fullHd'];
        });

        $backgroundHD = $this->getRandomImageUrl($images, function ($image) {
            return $image['hd'];
        });

        $backgroundLow = $this->getRandomImageUrl($images, function ($image) {
            return !$image['hd'] && !$image['fullHd'];
        });


        return $backgroundFullHD ?? $backgroundHD ?? $backgroundLow ?? null;
    }


    private function getMedias($game): Collection
    {
        $videos = $game->videos;
        $images = collect();
        $screenshots = $game->screenshots;
        if ($screenshots && $screenshots->isNotEmpty()) {
            $images = $images->concat($screenshots->map(function ($screenshot) {
                return [
                    'url' => $this->getImageUrl($screenshot),
                    'type' => 'screenshot',
                    'hd' => $screenshot->height > 720 && $screenshot->height < 1080,
                    'fullHd' => $screenshot->height >= 1080
                ];
            }));
        }
        $artworks = $game->artworks;

        if ($artworks && count($artworks) > 0) {
            $images = $images->concat(array_map(function ($artwork) {

                return [
                    'url' =>  $this->getImageUrl(Artwork::find($artwork['id'])),
                    'type' => 'artwork',
                    'hd' => $artwork['height'] > 720,
                    'fullHd' => $artwork['height'] > 1080
                ];
            }, $artworks));
        }
        return $videos->concat($images);
    }

    private function getCompaniesOfGame($involvedCompanies): Collection
    {
        return collect($involvedCompanies)->map(function ($company) {
            return InvolvedCompany::where('id', $company)->with(['company'])->first();
        });
    }

    private function getImageUrl($images)
    {
        $basePath = static::IMAGE_BASE_PATH;
        $id = $images->getAttribute('image_id');
        if ($id === null) {
            throw new Error('Property [image_id] is missing from the response. Make sure you specify `image_id` inside the fields attribute.');
        }
        $id = '' . $id;
        return "$basePath/t_original/$id.jpg";

    }

    private function getReviews($igdb_id)
    {
        $reviewsData = Review::where('igdb_id', $igdb_id)->with(['likes', 'comments'])->get();
        if($reviewsData->isEmpty()) {
            return [
                'reviews' => [],
                'sentimentScore' => [
                    'count' => 0,
                    'total' => 0
                ],
            ];
        }

        // Filter reviews with content
        $reviews = $reviewsData->filter(function ($data) {
            return $data->content !== null;
        })->map(function ($review) {
            return [
                'id' => $review->id,
                'content' => $review->content,
                'sentiment_score' => $review->sentiment_score,
                'user' => $review->user,
                'likes' => $review->likes,
                'comments' => $review->comments,
                'created_at' =>  Carbon::parse($review->created_at)->format('d M Y')
            ];
        });

        // Calculate sentiment score
        $sentimentScores = $reviewsData->pluck('sentiment_score');
        if ($sentimentScores->isNotEmpty()) {
            $countSentimentScore = $sentimentScores->count();
            $totalSentimentScore = $sentimentScores->sum() / $sentimentScores->count();
        }

        return [
            'reviews' => $reviews,
            'sentimentScore' => [
                'count' => $countSentimentScore ?? 0,
                'total' => $totalSentimentScore ?? 0
            ],
        ];
    }

    private function getGameUserInteractions($igdb_id)
    {
        $totalPlaying = 0;
        $totalWishlisted = 0;
        $totalPlayed = 0;
        $totalFavorite = 0;

        $userGameInteractionsData = UserGameInteraction::where('igdb_id', $igdb_id)->get();

        $status = $userGameInteractionsData->pluck('status');
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

        $isFavorite = $userGameInteractionsData->pluck('is_favorite');
        if ($isFavorite->isNotEmpty()) {
            $totalFavorite = $isFavorite->filter(function ($value) {
                return $value === true;
            })->count();
        }

        $gameInteractionByUser = $userGameInteractionsData->filter(function ($data) {
            return $data->user_id === auth()->id();
        })->first();


        return [
            'totalPlaying' => $totalPlaying,
            'totalWishlisted' => $totalWishlisted,
            'totalPlayed' => $totalPlayed,
            'totalFavorite' => $totalFavorite,
            'currentUser' => $gameInteractionByUser
        ];
    }
}
