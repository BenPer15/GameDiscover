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
        Schema::create('user_games', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignId('user_id')->constrained()->delete('cascade');
            $table->foreignUuid('game_id')->constrained()->delete('cascade');
            $table->integer('rating');
            $table->text('review');
            $table->boolean('is_wishlisted');
            $table->boolean('is_finished');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_games');
    }
};
