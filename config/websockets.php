<?php

return [
    /**
     * WebSocket broadcasting driver.
     * 
     * @example pusher|ably
     */
    'driver' => env('WEBSOCKET_DRIVER'),

    /**
     * Ably driver credentials.
     */
    'ably_key' => env('ABLY_KEY'),

    /**
     * Pusher driver credentials.
     */
    'pusher_app_id' => env('PUSHER_APP_ID'),
    'pusher_cluster' => env('PUSHER_CLUSTER'),
    'pusher_key' => env('PUSHER_KEY'),
    'pusher_secret' => env('PUSHER_SECRET_KEY'),
];
