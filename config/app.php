<?php

return [
    /**
     * Clean HTML output from line breks and indentations.
     */
    'compress' => env('OUTPUT_COMPRESS'),

    /**
     * Show exception page or HTTP 500 error.
     */
    'development' => env('APP_DEVELOPMENT'),

    /**
     * Application name.
     */
    'name' => env('APP_NAME'),

    /**
     * Root directory visible for users.
     */
    'public' => env('APP_PUBLIC'),
];
