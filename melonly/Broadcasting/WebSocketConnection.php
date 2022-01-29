<?php

namespace Melonly\Broadcasting;

use Pusher\Pusher as PusherDriver;

class WebSocketConnection implements WebSocketConnectionInterface {
    protected mixed $broadcaster = null;

    public function __construct() {
        switch (config('websockets.driver')) {
            case 'pusher':
                if (config('websockets.pusher_key') && config('websockets.pusher_secret') && config('websockets.pusher_app_id')) {
                    $this->broadcaster = new PusherDriver(config('websockets.pusher_key'), config('websockets.pusher_secret'), config('websockets.pusher_app_id'), [
                        'cluster' => config('websockets.pusher_cluster') ?? 'eu',
                        'useTLS' => true,
                    ]);
                }

                break;

            case 'ably':
                $settings = [
                    'key' => config('websockets.ably_key'),
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
