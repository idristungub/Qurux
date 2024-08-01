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
        Schema::create('quizstats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('chapter_title');
            $table->integer('chapter_number');
            $table->integer('verse_number')->nullable();
            $table->integer('juz_number')->nullable();
            $table->string('difficulty');
            $table->boolean('bookmarked')->default(false)->nullable();
            $table->boolean('recent')->default(false)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizstats');
    }
};
