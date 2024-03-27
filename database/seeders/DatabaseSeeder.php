<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Benoit',
            'email' => 'benoit.peron62@gmail.com',
        ]);
        $user->profile()->create([
            'platforms' => '["PS5", "PC"]',
            'genres' => '[]',
            'mode' => '[]',
        ]);
        $user->reviews()->create([
            'igdb_id' => 987,
            'content' => "I really enjoyed this game. It's a great game to play with friends.",
            'sentiment_score' => 80,
        ]);
        $user->gameInteraction()->create([
            'igdb_id' => 987,
            'is_favorite' => false,
            'status' => 'completed',
        ]);
    }
}
