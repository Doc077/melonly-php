<?php

namespace Melonly\Broadcasting;

use Exception;
use Ably\AblyRest as AblyDriver;
use Pusher\Pusher as PusherDriver;

class WebSocketConnection implements WebSocketConnectionInterface {
    protected PusherDriver | AblyDriver | null $broadcaster = null;

    public function __construct() {
        /**
         * Initialize driver.
         */
        switch (env('WEBSOCKET_DRIVER')) {
            case 'pusher':
                if (env('PUSHER_KEY') && env('PUSHER_SECRET_KEY') && env('PUSHER_APP_ID')) {
                    $this->broadcaster = new PusherDriver(env('PUSHER_KEY'), env('PUSHER_SECRET_KEY'), env('PUSHER_APP_ID'), [
                        'cluster' => env('PUSHER_CLUSTER') ?? 'eu',
                        'useTLS' => true
                    ]);
                }

                break;

            case 'ably':
                $settings = [
                    'key' => env('ABLY_KEY')
                ];

                $this->broadcaster = new AblyDriver($settings);

                break;

            default:
                throw new Exception('Unsupported broadcast driver');
        }
    }

    public function broadcast(string $channel, string $event, mixed $data): void {
        if ($this->broadcaster === null) {
            throw new Exception('Provide your broadcast driver credentials in .env file');
        }

        switch (env('WEBSOCKET_DRIVER')) {
            case 'pusher':
                $this->broadcaster->trigger($channel, $event, $data);

                break;
            case 'ably':
                $broadcastChannel = $this->broadcaster->channel($channel);

                $broadcastChannel->publish($event, $data);

                break;
        }
    }
}
