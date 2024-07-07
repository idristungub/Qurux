<?php

namespace App\library;

use Illuminate\Support\Facades\Http;

class FetchData
{
    protected $chapterData;
    protected $juzData;
    public function __construct() {
        $this->chapterData = [];
        $this->juzData = [];
    }
    public function fetchVerses($chapter)
    {
        $verseData = [];

        $this->fetchChapter();
        if($chapter) {
            $totalVerses = $this->chapterData['verses_count'];
        }

        for($verse = 1; $verse <= $totalVerses; $verse++) {
            $responseVerse = Http::get('https://quranapi.pages.dev/api/{$chapter}/{$verse}');
            if($responseVerse->successful()) {
                $data = $responseVerse->json();
                $verseData[] = [
                    'chapter_id' => $chapter,
                    'verse_id' => $verse,
                    'actual_verse' => $data['arabic1'],
                    'audio' => $data['audio'][1]['url']
                ];

            }
        }
        return $verseData;


    }

    public function fetchChapter()
    {
        $chapters = Http::get("https://api.quran.com/api/v4/chapters");
        $data = json_decode($chapters->body(), true);

        foreach ($data['chapters'] as $chapterdata) {

           return $this->chapterData = [
                'id' => $chapterdata['id'],
                'chapter_title' => 'Surah ' . $chapterdata['name_simple'],
                'verses_count' => $chapterdata['verses_count']
            ];
        }
    }

    public function fetchJuz($chapter_id)
    {
        $responseJuz = Http::get("https://api.quran.com/api/v4/juzs");
        $data = $responseJuz->json();

        foreach ($data['juzs'] as $juz) {

            $chapters = $juz['verse_mapping'];
            $firstverse = explode('-' ,$chapters[$chapter_id])[0];
            $lastverse = explode('-' ,$chapters[$chapter_id])[1];

            return $this->juzData = [
                'juzNumber' => $juz['juz_number'],
                'first_verse' => $firstverse,
                'last_verse' => $lastverse,
                'verses_count' => $juz['verses_count'],
            ];

        }

    }

}
