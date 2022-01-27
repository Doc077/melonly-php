<?php

use Melonly\Http\Request;
use Melonly\Http\Response;
use Melonly\Routing\Facades\Route;
use App\Services\UserService;

Route::get('/', function (Request $request, Response $response, UserService $class): void {
    $response->view('pages.home', [
        'ip' => $request->ip()
    ]);
});
