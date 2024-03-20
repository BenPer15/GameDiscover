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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('platforms')->default('[]');
            $table->text('genres')->default('[]');
            $table->text('mode')->default('[]');
            $table->text('x_id')->nullable();
            $table->text('insta_id')->nullable();
            $table->text('steam_id')->nullable();
            $table->text('epic_id')->nullable();
            $table->text('origin_id')->nullable();
            $table->text('discord_id')->nullable();
            $table->text('twitch_id')->nullable();
            $table->text('psn_id')->nullable();
            $table->text('switch_id')->nullable();
            $table->text('battleNet_id')->nullable();
            $table->text('uPlay_id')->nullable();
            $table->string('avatar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
