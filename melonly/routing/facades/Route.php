<?php

namespace Melonly\Routing;

use Melonly\Services\Facade;

class Route extends Facade {
    protected static function getAccessor(): string {
        return Router::class;
    }

    public static function __callStatic(string $method, array $args): mixed {
        return self::handleCall($method, $args, self::getAccessor());
    }
}
