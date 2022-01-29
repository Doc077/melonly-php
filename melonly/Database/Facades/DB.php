<?php

namespace Melonly\Database\Facades;

use Melonly\Container\Facade;
use Melonly\Database\DBConnection;

class DB extends Facade {
    protected static function getAccessor(): string {
        return DBConnection::class;
    }

    public static function __callStatic(string $method, array $args): mixed {
        return self::handleCall($method, $args, self::getAccessor());
    }
}
