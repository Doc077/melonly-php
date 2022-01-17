<?php

use Melonly\Http\Request;
use Melonly\Http\Response;
use Melonly\Routing\Route;

Route::get('/', function (Request $request, Response $response): void {
    $response->view('pages.home');
});
