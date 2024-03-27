<?php

namespace App\Services;

use Google\Cloud\Language\LanguageClient;

class GoogleCloudService
{
    public function reviewAnalyseSentiment($text)
    {
        $client = new LanguageClient([
            'projectId' => env('GOOGLE_CLOUD_PROJECT_ID'),
        ]);
        $annotation = $client->analyzeSentiment($text);
        $score = $annotation->sentiment()['score'];
        $magnitude = $annotation->sentiment()['magnitude'];
        $pourcentage_initial = (($score + 1) / 2) * 100;
        $score_ajuste = $pourcentage_initial * (1 + $magnitude / 10);
        return min(max($score_ajuste, 0), 100);
    }
}
