<?php

namespace Melonly\Validation;

use Melonly\Services\Facade;

class Validate extends Facade {
    protected static function getAccessor(): string {
        return Validator::class;
    }

    public static function __callStatic(string $method, array $args): mixed {
        return self::handleCall($method, $args, self::getAccessor());
    }
}
