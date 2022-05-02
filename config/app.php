<?php

return [
    /**
     * Clean HTML output from line breaks and indentation.
     */
    'compress' => env('OUTPUT_COMPRESS'),

    /**
     * Filesystem base directory.
     */
    'base_path' => directoryUp(__DIR__),

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
