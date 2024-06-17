<?php

namespace Database\Factories;

use App\Models\Quizstats;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quizstats>
 */
class QuizstatsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */


    protected static $versesData = [];

    protected static $chapterData = [];
    protected static $difficulty = ['easy', 'advance'];



    protected function fetchAndProcessVersesData()
    {
        $verses = Http::get("https://api.quran.com/api/v4/quran/verses/uthmani");
        $data = json_decode($verses->body(), true);

        foreach ($data['verses'] as $versedata) {
            $chapterNumber = explode(':', $versedata['verse_key'])[0];
            $verseNumber = explode(':', $versedata['verse_key'])[1];

            self::$versesData[] = [
                'chapter_number' => $chapterNumber,
                'verse_number' => $verseNumber,

            ];
        }
    }

    protected function fetchAndProcessChapterData()
    {
        $chapters = Http::get("https://api.quran.com/api/v4/chapters");
        $data = json_decode($chapters->body(), true);

        foreach ($data['chapters'] as $chapterdata) {

            self::$chapterData[] = [
                'id' => $chapterdata['id'],
                'chapter_title' => 'Surah ' . $chapterdata['name_simple'],
                'verses_count' => $chapterdata['verses_count']
            ];
        }
    }



    public function definition(): array
    {
        self::fetchAndProcessChapterData();

        $randomChapter = fake()->randomElement(self::$chapterData);

        $users = User::pluck('id');

        return [
            'user_id' => fake()->randomElement($users),
            'chapter_title' => $randomChapter['chapter_title'],
            'chapter_number' => $randomChapter['id'],
            'verse_number' => rand(1,$randomChapter['verses_count']),
            'difficulty' => fake()->randomElement(self::$difficulty),
            'health_status' => fake()->numberBetween(0, 3 ),
            'bookmarked' => fake()->randomElement([true, false])

        ];
    }
}
