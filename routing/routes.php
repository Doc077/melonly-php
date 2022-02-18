<?php

use App\Controllers;
use Melonly\Http\Request;
use Melonly\Http\Response;
use Melonly\Routing\Facades\Route;

Route::get('/', function (Request $request, Response $response): void {
    $response->view('pages.home');
});

/**
 * Example route with assigned controller.
 * 
 * Controllers are stored in src/Controllers.
 * Create a new controller with new:controller command.
 */
Route::get('/example/{slug}', [Controllers\ExampleController::class, 'index']);
