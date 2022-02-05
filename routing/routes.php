<?php

use App\Controllers;
use Melonly\Http\Request;
use Melonly\Http\Response;
use Melonly\Routing\Facades\Route;
use App\Models\User;

Route::get('/', function (Request $request, Response $response): void {
    die(User::where('id', '=', 1)->fetch()->name);
    $response->view('pages.home', [
        'ip' => $request->ip(),
    ]);
});

Route::get('/users/{id}', function (Request $request, Response $response): void {
    $response->send('User id: ', $request->parameter('id'));
});

Route::get('/example', [Controllers\ExampleController::class, 'index']);
