<?php

use App\Controllers;
use Melonly\Http\Request;
use Melonly\Http\Response;
use Melonly\Routing\Facades\Route;
throw new \Exception('grg');
Route::get('/', function (Request $request, Response $response): void {
    $response->view('pages.home', ['ip' => $request->ip()]);
});

# Controller route example
Route::get('/example/{slug}', [Controllers\ExampleController::class, 'index']);
