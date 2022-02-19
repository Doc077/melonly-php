<?php

namespace Melonly\Broadcasting\Facades;

use Melonly\Container\Facade;

/**
 * @method static void broadcast(string $channel, string $event, mixed $data)
 */
class WebSocket extends Facade
{
    protected static function getAccessor(): string
    {
        return WebSocketConnection::class;
    }

    public static function __callStatic(string $method, array $args): mixed
    {
        return self::handleCall($method, $args, self::getAccessor());
    }
}
