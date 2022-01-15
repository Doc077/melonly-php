<?php

namespace Melonly\Services;

abstract class Facade {
    protected static function handleCall(string $method, array $args, string $accessor): mixed {
        $instance = Container::get($accessor);

        return $instance->$method(...$args);
    }
}
