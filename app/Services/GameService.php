<?php

namespace App\Services;

use App\Models\Review;
use App\Models\UserGameInteraction;
use Carbon\Carbon;
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
    public function search(string $gameName): Collection
    {
        try {
            $igdbGames = IGDBGame::fuzzySearch(['name'], $gameName)
                ->where('platforms', '!=', null)
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
                ->where('platforms', '!=', null)
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
        $game = IGDBGame::select(['name', 'summary', 'first_release_date', 'cover', 'platforms', 'involved_companies', 'screenshots', 'artworks', 'genres', 'game_modes'])
            ->where('id', '=', $id)
            ->with(['cover', 'platforms' => ['abbreviation', 'id'], 'genres' => ['name'], 'screenshots', 'artworks','game_modes'])
            ->first();

        if (!$game) {
            return collect([]);
        }

        $reviews = $this->getReviews($game->id);
        $images = $this->getImagesOfGame($game);
        $game->reviews = $reviews['reviews'] ?? [];
        $game->sentimentsScore = $reviews['sentimentScore'];
        $game->gameUserInteractions = $this->getGameUserInteractions($game->id);
        $game->involved_companies = $this->getCompaniesOfGame($game->involved_companies ?? []);
        $game->medias = $images;
        $game->background = $this->getBackgroundOfGame($images);
        $game->stream = $this->getStream($game['id']);
        $game->coverImg = $game->cover ? $game->cover->getUrl(Size::ORIGINAL) : 'https://via.placeholder.com/264x352?text=No+Cover';
        $game->year_release_date = $game->first_release_date ? Carbon::parse($game->first_release_date)->format('Y') : '';
        $game->release_date = $game->first_release_date ? Carbon::parse($game->first_release_date)->format('d M Y') : '';
        return $game;
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


    private function getImagesOfGame($game): Collection
    {
        $images = collect();
        $screenshots = $game->screenshots;
        if ($screenshots && $screenshots->isNotEmpty()) {
            $images = $images->concat($screenshots->map(function ($screenshot) {
                return [
                    'url' => $screenshot->getUrl(Size::ORIGINAL),
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
                   'url' =>  Artwork::find($artwork['id'])->getUrl(Size::ORIGINAL),
                   'type' => 'artwork',
                   'hd' => $artwork['height'] > 720,
                'fullHd' => $artwork['height'] > 1080
                ];
            }, $artworks));
        }
        return $images;
    }

    private function getCompaniesOfGame($involvedCompanies): Collection
    {
        return collect($involvedCompanies)->map(function ($company) {
            return InvolvedCompany::where('id', $company)->with(['company'])->first();
        });
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
