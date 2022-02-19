<?php

namespace Melonly\Broadcasting;

use Ably\AblyRest;
use Pusher\Pusher;

class WebSocketConnection implements WebSocketConnectionInterface
{
    protected mixed $broadcaster = null;

    public function __construct()
    {
        switch (config('websocket.driver')) {
            case 'pusher':
                if (config('websocket.pusher_key') && config('websocket.pusher_secret') && config('websocket.pusher_id')) {
                    $this->broadcaster = new Pusher(config('websocket.pusher_key'), config('websocket.pusher_secret'), config('websocket.pusher_id'), [
                        'cluster' => config('websocket.pusher_cluster') ?? 'eu',
                        'useTLS' => true,
                    ]);
                }

                break;

            case 'ably':
                $settings = [
                    'key' => config('websocket.ably_key'),
                ];

                $this->broadcaster = new AblyRest($settings);

                break;

            default:
                throw new WebSocketDriverException('Unsupported broadcast driver');
        }
    }

    public function broadcast(string $channel, string $event, mixed $data): void
    {
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
