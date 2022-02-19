<?php

namespace Melonly\Support\Helpers;

class Url
{
    public static function full(): string
    {
        $url = (isset($_SERVER['HTTPS']) &&
            $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
                . '://' . (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '')
                . (!empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : ''
        );

        return $url;
    }

    public static function path(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    public static function port(): string
    {
        return $_SERVER['SERVER_PORT'];
    }

    public static function previous(): string
    {
        $url = $_SERVER['REQUEST_URI'];

        if (isset($_SERVER['HTTP_REFERER'])) {
            $url = $_SERVER['HTTP_REFERER'];
        }

        return $url;
    }
}
