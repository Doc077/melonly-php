<?php

use App\Controllers;
use Melonly\Http\Request;
use Melonly\Http\Response;
use Melonly\Routing\Facades\Route;

Route::get('/', function (Request $request, Response $response): void {throw new \Exception('grgr');
    $response->view('pages.home', ['ip' => $request->ip()]);
});

# Controller route example
Route::get('/example/{slug}', [Controllers\ExampleController::class, 'index']);
