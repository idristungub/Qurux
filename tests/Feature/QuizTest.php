<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QuizTest extends TestCase
{
    use RefreshDatabase;

    public function test_health_status_zero()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create([
            'health_status' => 0
        ]);

        $this->actingAs($user);

        $response = $this->get('/quran-quest');
        $response->assertStatus(200);


        $this->assertEquals(3, $user->health_status, 'health status resets to 3 health again when redirecting to home');

    }

    public function test_points_to_user_easy_quiz(){
        $this->withoutExceptionHandling();
        $user = User::factory()->create([
            'points' => 2
        ]);

        $this->actingAs($user);

        $easyQuizResponse = $this->post('/checkPointsEasy');
        $easyQuizResponse->assertStatus(200);


        // test easy quiz gain points by 1
        $this->assertEquals(3, $user->points, 'user gains 1 points when getting it correct for easy quiz');



    }

    public function test_points_to_user_advance_quiz()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create([
            'points' => 0
        ]);
        $this->actingAs($user);

        $advancedQuizResponse = $this->post('/checkPointsAdvance');
        $advancedQuizResponse->assertStatus(200);

        // test advance quiz gain points by 5
        $this->assertEquals(5, $user->points, 'user gains 5 points when getting it correct for advanced quiz');

    }



}
