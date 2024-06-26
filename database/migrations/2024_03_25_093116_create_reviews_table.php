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
        Schema::create('reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('igdb_id')->index();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('sentiment_score')->nullable()->index();
            $table->text('content')->nullable();
            $table->string('platform')->nullable();
            $table->boolean('is_spoiler')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
