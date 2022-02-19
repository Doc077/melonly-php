<?php

namespace Melonly\Http;

use Melonly\Container\Container;

class Controller
{
    protected function render(string $view, array $variables = []): void
    {
        Container::get(Response::class)->view($view, $variables);
    }
}
