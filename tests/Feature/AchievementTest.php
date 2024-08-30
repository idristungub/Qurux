<?php

namespace Tests\Feature;

use App\Models\AchievedUser;
use App\Models\Achievement;
use App\Models\Quizstats;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AchievementTest extends TestCase
{
    use RefreshDatabase;

    public function test_achievement_is_unlocked_10_bookmarks()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $achievement = Achievement::factory()->create([
            'achievement_title' => 'Bookmark 10 Surahs/Juz'
        ]);
        $bookmark = Quizstats::factory(10)->make([
            'bookmarked' => true
        ]);
        if($bookmark) {
            AchievedUser::factory()->create([
                'users_id' => $user->id,
                'achievements_id' => $achievement->id,
                'completed' => true
            ]);
            $this->assertDatabaseHas('achieved_users', [
                'users_id' => $user->id,
                'achievements_id' => $achievement->id,
                'completed' => true
            ]);
        }
    }

    public function test_achievement_is_unlocked_top_3_leaderboard()
    {
        $user = User::factory()->create();
        $achievement = Achievement::factory()->create([
            'achievement_title' => 'Reach top 3 in the leaderboard'
        ]);
        $top3 = User::factory(3)->create();
        $this->actingAs($user);
        $points = assert($user->points + 10000000);

        if($top3->has([
            'points' => $points
        ])) {
            AchievedUser::factory()->create([
                'users_id' => $user->id,
                'achievements_id' => $achievement->id,
                'completed' => true
            ]);
            $this->assertDatabaseHas('achieved_users', [
                'users_id' => $user->id,
                'achievements_id' => $achievement->id,
                'completed' => true
            ]);
        }
    }
}
