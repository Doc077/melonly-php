<?php

namespace Melonly\Http;

use Melonly\Container\Container;
use Melonly\Http\Response;

class Controller {
    protected function render(string $view, array $variables = []): void {
        Container::get(Response::class)->view($view, $variables);
    }
}
