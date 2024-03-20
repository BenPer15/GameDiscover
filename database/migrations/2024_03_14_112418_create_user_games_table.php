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
            $table->id('igdb_id');
            $table->integer('rating')->nullable();
            $table->text('review')->nullable();
            $table->boolean('is_favorite')->default(false);
            $table->string('status')->nullable(); // wishlisted, completed, playing, played, dropped
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
