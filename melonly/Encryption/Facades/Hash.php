<?php

namespace Melonly\Encryption\Facades;

use Melonly\Container\Facade;
use Melonly\Encryption\Hasher;

/**
 * @method static string hash(string $data, int $cost = 10)
 * @method static bool check(string $input, string $output)
 * @method static bool equals(string $input, string $output)
 */
class Hash extends Facade
{
    protected static function getAccessor(): string
    {
        return Hasher::class;
    }

    public static function __callStatic(string $method, array $args): mixed
    {
        return self::handleCall($method, $args, self::getAccessor());
    }
}
