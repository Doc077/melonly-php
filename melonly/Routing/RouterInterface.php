<?php

namespace Melonly\Routing;

use Melonly\Http\Method as HttpMethod;

interface RouterInterface {
    public function add(string|HttpMethod $method, string $uri, callable $action, array $data = []): void;

    public function get(string $uri, callable $action, array $data = []): void;

    public function post(string $uri, callable $action, array $data = []): void;

    public function put(string $uri, callable $action, array $data = []): void;

    public function patch(string $uri, callable $action, array $data = []): void;

    public function delete(string $uri, callable $action, array $data = []): void;

    public function options(string $uri, callable $action, array $data = []): void;

    public function any(string $uri, callable $action, array $data = []): void;

    public function evaluate(): void;
}
