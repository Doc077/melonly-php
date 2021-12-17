<?php

namespace Melonly\Broadcasting;

use Melonly\Services\Container;

class WebSocket implements WebSocketInterface {
    public static function broadcast(string $channel, string $event, mixed $data): void {
        Container::get(WebSocketConnection::class)->broadcast($channel, $event, $data);
    }
}
