<?php

namespace Melonly\Translation\Facades;

use Melonly\Container\Facade;
use Melonly\Translation\Translator;

/**
 * @method static string getCurrent()
 * @method static void setCurrent(string $lang)
 * @method static string getTranslation(string $key)
 */
class Lang extends Facade
{
    protected static function getAccessor(): string
    {
        return Translator::class;
    }

    public static function __callStatic(string $method, array $args): mixed
    {
        return self::handleCall($method, $args, self::getAccessor());
    }
}
