<?php

namespace Melonly\Encryption;

use Melonly\Services\Facade;

class Crypt extends Facade {
    protected static function getAccessor(): string {
        return Encrypter::class;
    }

    public static function __callStatic(string $method, array $args): mixed {
        return self::handleCall($method, $args, self::getAccessor());
    }
}
