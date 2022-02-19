<?php

namespace Melonly\Database\Facades;

use Melonly\Container\Facade;
use Melonly\Database\Connection;

/**
 * @method static object|array query(string $sql, string $modelClass = Record::class, array $boundParams = [])
 * @method static \Melonly\Database\Query from(string $table)
 * @method static \PDO getConnection()
 */
class DB extends Facade
{
    protected static function getAccessor(): string
    {
        return Connection::class;
    }

    public static function __callStatic(string $method, array $args): mixed
    {
        return self::handleCall($method, $args, self::getAccessor());
    }
}
