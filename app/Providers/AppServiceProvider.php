<?php

namespace App\Providers;

use App\Services\GameService;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(GameService::class, function ($app) {
            return new GameService(
                $app->make(Carbon::class),
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $filePath = storage_path('app/google-credentials.json');
        if (!file_exists($filePath) && env('GOOGLE_APPLICATION_CREDENTIALS_BASE64')) {
            // Décoder la clé d'API et créer le fichier.
            file_put_contents($filePath, base64_decode(env('GOOGLE_APPLICATION_CREDENTIALS_BASE64')));
        }
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $filePath);
    }
}
