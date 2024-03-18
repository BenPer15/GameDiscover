<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_notification_preferences', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('email')->default(true);
            $table->boolean('new_game_releases')->default(true);
            $table->boolean('game_updates')->default(true);
            $table->boolean('friend_requests')->default(true);
            $table->boolean('special_offers')->default(true);
            $table->boolean('weekly_newsletter')->default(true);
            $table->boolean('events')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notification_preferences');
    }
};
