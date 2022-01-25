<?php

namespace Melonly\Broadcasting;

use Pusher\Pusher as PusherDriver;

class WebSocketConnection implements WebSocketConnectionInterface {
    protected mixed $broadcaster = null;

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

                if (class_exists('Ably\AblyRest')) {
                    $this->broadcaster = new ('\Ably\AblyRest')($settings);
                }

                break;

            default:
                throw new WebSocketDriverException('Unsupported broadcast driver');
        }
    }

    public function broadcast(string $channel, string $event, mixed $data): void {
        if ($this->broadcaster === null) {
            throw new WebSocketDriverException('.env broadcasting credentials not supplied or driver package is not installed');
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
