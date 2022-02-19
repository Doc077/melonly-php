<?php

namespace Melonly\Encryption\Facades;

use Melonly\Container\Facade;
use Melonly\Encryption\Encrypter;

/**
 * @method static string encrypt(string $data, string $algorithm = 'aes-256-ctr', bool $encode = false)
 * @method static string decrypt(string $data, string $algorithm = 'aes-256-ctr', bool $encoded = false)
 */
class Crypt extends Facade
{
    protected static function getAccessor(): string
    {
        return Encrypter::class;
    }

    public static function __callStatic(string $method, array $args): mixed
    {
        return self::handleCall($method, $args, self::getAccessor());
    }
}
