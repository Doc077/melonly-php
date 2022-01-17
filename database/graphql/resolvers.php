<?php

use Melonly\GraphQL\GraphQL;

return [
    'User' => [
        // 
    ],
    'Query' => [
        'getUsers' => function ($root, $arguments, $context) {
            return $context;
        }
    ]
];
