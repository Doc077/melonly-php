<?php

namespace Melonly\Services;

abstract class Facade {
    protected static string $accessor;

    public static function __callStatic(string $method, array $args): mixed {
        $instance = Container::get(self::$accessor);

        return $instance->$method(...$args);
    }
}
