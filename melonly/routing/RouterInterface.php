<?php

namespace Melonly\Routing;

interface RouterInterface {
    public function add(string $method, string $uri, callable $action, array $data = []): void;

    public static function get(string $uri, callable $action, array $data = []): void;

    public static function post(string $uri, callable $action, array $data = []): void;

    public static function put(string $uri, callable $action, array $data = []): void;

    public static function patch(string $uri, callable $action, array $data = []): void;

    public static function delete(string $uri, callable $action, array $data = []): void;

    public static function options(string $uri, callable $action, array $data = []): void;

    public static function any(string $uri, callable $action, array $data = []): void;

    public function evaluate(): void;
}
