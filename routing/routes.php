<?php

use Melonly\Http\Request;
use Melonly\Http\Response;
use Melonly\Routing\Facades\Route;
use Melonly\Database\Facades\DB;

Route::get('/', function (Request $request, Response $response): void {
    echo DB::query('SELECT name FROM users WHERE id = 1');
    $response->view('pages.home', [
        'ip' => $request->ip()
    ]);
});
