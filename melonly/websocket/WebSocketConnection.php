<?php

namespace Melonly\Broadcasting;

use Exception;
use Pusher\Pusher as PusherDriver;
use Ably\AblyRest as AblyDriver;

class WebSocketConnection {
    protected PusherDriver | AblyDriver | null $broadcaster = null;

    public function __construct() {
        /**
         * Initialize driver.
         */
        if (env('WEBSOCKET_DRIVER') === 'pusher') {
            if (env('PUSHER_KEY') && env('PUSHER_SECRET_KEY') && env('PUSHER_APP_ID')) {
                $this->broadcaster = new PusherDriver(env('PUSHER_KEY'), env('PUSHER_SECRET_KEY'), env('PUSHER_APP_ID'), [
                    'cluster' => env('PUSHER_CLUSTER') ?? 'eu',
                    'useTLS' => true
                ]);
            }
        } elseif (env('WEBSOCKET_DRIVER') === 'ably') {
            $settings = [
                'key' => env('ABLY_KEY')
            ];

            $this->broadcaster = new AblyDriver($settings);
        }
    }

    public function broadcast(string $channel, string $event, mixed $data): void {
        if ($this->broadcaster === null) {
            throw new Exception('Provide your broadcast credentials in .env file');
        }

        if (env('WEBSOCKET_DRIVER') === 'pusher') {
            $this->broadcaster->trigger($channel, $event, $data);
        } elseif (env('WEBSOCKET_DRIVER') === 'ably') {
            $broadcastChannel = $this->broadcaster->channel($channel);

            $broadcastChannel->publish($event, $data);
        }
    }
}
