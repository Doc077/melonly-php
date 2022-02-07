<?php

return [
    /**
     * HTTP middleware.
     */
    'middleware' => [
        'auth' => \App\Middleware\Authenticated::class,
        'guest' => \App\Middleware\Guest::class,
    ],
];
