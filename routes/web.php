<?php

use Melonly\Routing\Route;
use Melonly\Http\Request;
use Melonly\Http\Response;

Route::get('/', function (Request $request, Response $response): void {
    $response->view('pages.home');
});
