<?php

namespace App\Helpers;

class ExcerptHelper
{
    /**
     * Generate excerpt dari sebuah text.
     *
     * @param  string  $text
     * @param  int     $limit
     * @return string
     */
    public static function makeExcerpt($text, $limit = 150)
    {
        $text = strip_tags($text);

        if (strlen($text) <= $limit) {
            return $text;
        }

        $excerpt = substr($text, 0, $limit);

        $lastSpace = strrpos($excerpt, ' ');
        if ($lastSpace !== false) {
            $excerpt = substr($excerpt, 0, $lastSpace);
        }

        return $excerpt . '...';
    }
}
