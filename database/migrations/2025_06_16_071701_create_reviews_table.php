<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->foreignId('platform_id')->nullable()->constrained()->onDelete('set null');
            $table->text('text')->nullable();
            $table->tinyInteger('rating')->unsigned()->nullable();
            $table->enum('status', ['planned', 'played', 'dropped', 'completed', 'shelved']);
            $table->boolean('liked')->default(false);
            $table->unique(['user_id', 'game_id']);
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
