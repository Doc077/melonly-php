<?php

namespace Melonly\Authentication\Facades;

use Melonly\Authentication\Authenticator;
use Melonly\Container\Facade;

class Auth extends Facade {
    protected static function getAccessor(): string {
        return Authenticator::class;
    }

    public static function __callStatic(string $method, array $args): mixed {
        return self::handleCall($method, $args, self::getAccessor());
    }
}
