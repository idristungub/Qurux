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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->longText('achievement_title');
            $table->timestamps();
        });

        // inserting achievements
        DB::table('achievements')->insert(
            array(
                'achievement_title' => 'Reach top 3 in the leaderboard',
                'created_at' => now(),
                'updated_at' => now()
            )
        );
        DB::table('achievements')->insert(
            array(
                'achievement_title' => 'Bookmark 10 Surahs/Juz',
                'created_at' => now(),
                'updated_at' => now()
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
