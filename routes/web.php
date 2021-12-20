<?php

use Melonly\Routing\Router;
use Melonly\Http\Request;
use Melonly\Http\Response;
use Melonly\Validation\Validator;

Router::get('/', function (Request $request, Response $response): void {
    $response->view('pages.home');
});

// Router::get('/login', function (Request $request, Response $response): void {
//     Validator::evaluate([
//         'username' => ['required', 'min:3', 'max:32']
//     ]);
// });
