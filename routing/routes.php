<?php

use Melonly\Http\Request;
use Melonly\Http\Response;
use Melonly\Routing\Facades\Route;

Route::get('/', function (Request $request, Response $response): void {
    $response->view('pages.home', [
        'ip' => $request->ip(),
    ]);
});

Route::get('/users/{id}', function (Request $request, Response $response): void {
    $response->send('User id: ', $request->parameter('id'));
});
