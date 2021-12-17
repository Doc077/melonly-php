<?php

namespace Melonly\Broadcasting;

use Exception;
use Pusher\Pusher;

class WebSocketConnection {
    protected null | Pusher $pusher = null;

    public function __construct() {
        if (env('PUSHER_KEY') && env('PUSHER_SECRET_KEY') && env('PUSHER_APP_ID')) {
            $this->pusher = new Pusher(env('PUSHER_KEY'), env('PUSHER_SECRET_KEY'), env('PUSHER_APP_ID'), [
                'cluster' => env('PUSHER_CLUSTER') ?? 'eu',
                'useTLS' => true
            ]);
        }
    }

    public function broadcast(string $channel, string $event, mixed $data): void {
        if ($this->pusher === null) {
            throw new Exception('Provide your Pusher credentials in .env file');
        }

        $this->pusher->trigger($channel, $event, $data);
    }
}
