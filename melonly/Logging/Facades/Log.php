<?php

namespace Melonly\Logging\Facades;

use Melonly\Container\Facade;

/**
 * @method static void error(string $data, string $file)
 * @method static int|false write(string $data, string $file)
 */
class Log extends Facade
{
    protected static function getAccessor(): string
    {
        return Logger::class;
    }

    public static function __callStatic(string $method, array $args): mixed
    {
        return self::handleCall($method, $args, self::getAccessor());
    }
}
