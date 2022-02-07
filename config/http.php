<?php

return [
    /**
     * HTTP middleware.
     */
    'middleware' => [
        'auth' => \App\Middleware\Authenticated::class,
    ],
];
