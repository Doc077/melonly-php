<?php

namespace Melonly\Broadcasting;

interface WebSocketInterface {
    public static function broadcast(string $channel, string $event, mixed $data): void;
}
