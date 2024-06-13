<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HttpRequestTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_post_quiz_easy(): void
    {
        $response = $this->call('POST','/quiz/{surah}/{verse}/easy', ['surah' => 'surah Ghafir', 'verse' => 'hey']);

        $this->assertEquals(302, $response->getStatusCode());

    }


}
