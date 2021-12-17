<?php

namespace Melonly\Localization;

class Lang {
    protected static $currentLanguage = 'en';

    public static function getCurrent(): string {
        return self::$currentLanguage;
    }

    public static function setCurrent(string $lang): void {
        self::$currentLanguage = $lang;
    }
}
