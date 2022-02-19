<?php

namespace Melonly\Routing;

use Melonly\Http\Method as HttpMethod;

interface RouterInterface
{
    public function add(string|HttpMethod $method, string $uri, callable|array $action, array $data = []): void;

    public function get(string $uri, callable|array $action, array $data = []): void;

    public function post(string $uri, callable|array $action, array $data = []): void;

    public function put(string $uri, callable|array $action, array $data = []): void;

    public function patch(string $uri, callable|array $action, array $data = []): void;

    public function delete(string $uri, callable|array $action, array $data = []): void;

    public function options(string $uri, callable|array $action, array $data = []): void;

    public function any(string $uri, callable|array $action, array $data = []): void;

    public function view(string $uri, string $view, array $variables = [], array $data = []): void;

    public function evaluate(): void;
}
