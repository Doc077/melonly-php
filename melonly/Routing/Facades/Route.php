<?php

namespace Melonly\Routing\Facades;

use Melonly\Container\Facade;
use Melonly\Routing\Router;

/**
 * @method static void add(string $method, string $uri, callable $action, array $data = [])
 * @method static void get(string $uri, callable $action, array $data = [])
 * @method static void post(string $uri, callable $action, array $data = [])
 * @method static void put(string $uri, callable $action, array $data = [])
 * @method static void patch(string $uri, callable $action, array $data = [])
 * @method static void delete(string $uri, callable $action, array $data = [])
 * @method static void options(string $uri, callable $action, array $data = [])
 * @method static void any(string $uri, callable $action, array $data = [])
 * @method static void view(string $uri, string $view, array $variables = [], array $data)
 * @method static void evaluate()
 */
class Route extends Facade
{
    protected static function getAccessor(): string
    {
        return Router::class;
    }

    public static function __callStatic(string $method, array $args): mixed
    {
        return self::handleCall($method, $args, self::getAccessor());
    }
}
