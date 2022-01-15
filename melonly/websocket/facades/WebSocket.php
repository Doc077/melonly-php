<?php

namespace Melonly\Broadcasting;

use Melonly\Services\Facade;

class WebSocket extends Facade {
    protected static function getAccessor(): string {
        return WebSocketConnection::class;
    }

    public static function __callStatic(string $method, array $args): mixed {
        return self::handleCall($method, $args, self::getAccessor());
    }
}