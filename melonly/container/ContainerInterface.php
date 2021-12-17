<?php

namespace Melonly\Services;

interface ContainerInterface {
    public static function get(string $key): mixed;

    public static function has(string $key): bool;

    public static function initialize(): void;
}
