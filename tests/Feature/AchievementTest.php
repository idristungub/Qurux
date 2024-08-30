<?php

namespace Tests\Feature;

use App\Models\Achievement;
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
            'id' => 1,
            'achievement_title' =>
        ]);
    }
}
