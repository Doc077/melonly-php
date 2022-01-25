<?php

namespace Melonly\Broadcasting;

use Melonly\Container\Facade;

class WebSocket extends Facade {
    protected static function getAccessor(): string {
        return WebSocketConnection::class;
    }

    public static function __callStatic(string $method, array $args): mixed {
        return self::handleCall($method, $args, self::getAccessor());
    }
}
