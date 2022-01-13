<?php

namespace Melonly\Broadcasting;

use Melonly\Services\Facade;

class WebSocket extends Facade {
    protected static string $accessor = WebSocketConnection::class;

    public static function __callStatic(string $method, array $args): mixed {
        parent::__callStatic($method, $args);
    }
}
