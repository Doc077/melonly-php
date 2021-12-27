<?php

use Melonly\Routing\Router;
use Melonly\Http\Request;
use Melonly\Http\Response;
use Melonly\Validation\Validator;
use Melonly\Database\DB;
//echo vect(1, 2, 3)->map(fn ($item, $i) => $item + $i)[1];
Router::get('/', function (Request $request, Response $response): void {
    $name = DB::query('SELECT name from users WHERE id = 1')->name;

    $response->view('pages.home', [
        'name' => $name
    ]);
});

Router::post('/login', function (Request $request, Response $response): void {
    Validator::evaluate([
        'username' => ['required', 'min:3', 'max:32']
    ]);
});
