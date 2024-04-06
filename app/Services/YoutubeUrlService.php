<?php

namespace App\Services;

class YoutubeUrlService
{
    public static function isYoutube($string)
    {
        if (!preg_match('/^[a-zA-Z0-9_-]{10,11}$/', $string)) {
            return false;
        }

        return true;
    }

    public static function googleToYoutube($string)
    {
        if (
            $host = parse_url($string, PHP_URL_HOST)
            and strpos($host, '.google.') !== false
            and $query = parse_url($string, PHP_URL_QUERY)
            and (parse_str($query, $parsedQuery) or true)
            and $parsedQuery
            and isset($parsedQuery['url'])
        ) {
            return $parsedQuery['url'];
        }

        return null;
    }

    public static function youtubeToId($string)
    {
        if (preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $string, $matches) > 0) {
            return reset($matches);
        }

        return null;
    }

    public static function googleToId($string)
    {
        if (
            $youtube = static::googleToYoutube($string)
            and $id = static::youtubeToId($youtube)
        ) {
            return $id;
        }

        return null;
    }

    public static function parse($string)
    {
        if ($id = static::googleToId($string) and static::isYoutube($id)) {
            return $id;
        }

        if ($id = static::youtubeToId($string) and static::isYoutube($id)) {
            return $id;
        }
        if (static::isYoutube($string)) {
            return $string;
        }

        return null;
    }
}
