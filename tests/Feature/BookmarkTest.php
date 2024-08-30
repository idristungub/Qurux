<?php

namespace Tests\Feature;

use App\Models\Quizstats;
use http\Client\Curl\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookmarkTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;

    public function test_user_can_delete_a_bookmark()
    {
        $user = \App\Models\User::factory()->create();

        $this->actingAs($user);

        $bookmark = Quizstats::factory()->create([
            'user_id' => $user->id,
            'chapter_title' => 'Al-Furqan',
            'chapter_number' => 25,
            'verse_number' => 45,
            // juz number can be nullable
            'difficulty' => 'easy',
            'bookmarked' => true,
            'recent' => true
        ]);

        $bookmark->delete();
        $this->assertTrue(true);
        $this->assertDatabaseMissing('quizstats', [
            'id' => $bookmark->id,
        ]);
    }

    public function test_user_can_post_a_bookmark() {

        $bookmark = Quizstats::factory()->create([
            'user_id' => 2,
            'chapter_title' => 'Al-fatiha',
            'chapter_number' => 1,
            'verse_number' => 4,
            'difficulty' => 'easy',
            'bookmarked' => true,
            'recent' => true
        ]);
        $bookmark->save();
        $this->assertTrue(true);

    }
}
