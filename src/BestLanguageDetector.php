<?php

namespace App;

use LanguageDetection\Language;

class BestLanguageDetector
{
    const MAPPING = [
        'en' => 'english',
        'ru' => 'russian',
    ];
    /**
     * @var Language
     */
    private $language;

    /**
     * BestLanguageDetector constructor.
     *
     * @param Language $language
     */
    public function __construct(Language $language = null)
    {
        $this->language = $language ?? new Language(array_keys(self::MAPPING));
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function detect(string $text): string
    {
        $ratings = $this->language->detect($text);

        $bestRating = 0;
        $bestLang = '';

        foreach ($ratings as $lang => $rating) {
            if ($rating >= $bestRating) {
                $bestLang = $lang;
                $bestRating = $rating;
            }
        }

        return self::MAPPING[$bestLang];
    }
}
