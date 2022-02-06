<?php

use App\Controllers;
use Melonly\Http\Request;
use Melonly\Http\Response;
use Melonly\Routing\Facades\Route;

Route::get('/', function (Request $request, Response $response): void {
    $response->view('pages.home', [
        'ip' => $request->ip(),
    ]);
});

/** Controller route example. */
Route::get('/example/{id}', [Controllers\ExampleController::class, 'index']);
