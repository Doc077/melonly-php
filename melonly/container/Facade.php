<?php

namespace Melonly\Services;

abstract class Facade {
    abstract protected static function getAccessor(): string;

    public static function __callStatic(string $method, array $args): mixed {
        $instance = Container::get(self::getAccessor());

        return $instance->$method(...$args);
    }
}
