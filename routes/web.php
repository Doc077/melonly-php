<?php

use Melonly\Routing\Router;
use Melonly\Http\Request;
use Melonly\Http\Response;

Router::get('/', function (Request $request, Response $response): void {
    $response->view('pages.home');
});
