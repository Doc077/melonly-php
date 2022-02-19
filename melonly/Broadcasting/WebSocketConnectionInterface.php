<?php

namespace Melonly\Broadcasting;

interface WebSocketConnectionInterface
{
    public function broadcast(string $channel, string $event, mixed $data): void;
}
