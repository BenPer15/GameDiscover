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
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $user->profile()->create([
            'platforms' => '["PS5", "PC"]',
            'genres' => '[]',
            'mode' => '[]',
        ]);
        $user->games()->create([
            'igdb_id' => 987,
            'rating' => 4,
            'review' => "I really enjoyed this game. It's a great game to play with friends.",
            'is_favorite' => true,
            'is_wishlisted' => false,
            'is_finished' => false,
        ]);
    }
}
