<?php

namespace Melonly\Encryption;

use Melonly\Services\Facade;

class Hash extends Facade {
    protected static function getAccessor(): string {
        return Hasher::class;
    }

    public static function __callStatic(string $method, array $args): mixed {
        return self::handleCall($method, $args, self::getAccessor());
    }
}
