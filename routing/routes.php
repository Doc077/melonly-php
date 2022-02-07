<?php

use App\Controllers;
use Melonly\Http\Request;
use Melonly\Http\Response;
use Melonly\Routing\Facades\Route;

/** Callback based routing */
Route::get('/', function (Request $request, Response $response): void {
    $response->view('pages.home', [
        'ip' => $request->ip(),
    ]);
}, ['middleware' => 'auth']);

/** Controller route example */
Route::get('/example/{slug}', [Controllers\ExampleController::class, 'index']);
