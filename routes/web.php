<?php

use Melonly\Routing\Router;
use Melonly\Http\Request;
use Melonly\Http\Response;
use Melonly\Validation\Validator;
use Melonly\Database\DB;
use App\Models\User;
//echo vect(1, 2, 3)->map(fn ($item, $i) => $item + $i)[1];
Router::get('/', function (Request $request, Response $response): void {
    //$name = DB::query('SELECT name from users WHERE id = 1')->name;
    $user = User::where('id', '=', 1)->orWhere('age', '>', 10)->where('name', '<>', 'Admin')->fetch();

    $response->view('pages.home', [
        'name' => $user->name
    ]);
});

// Router::post('/login', function (Request $request, Response $response): void {
//     Validator::rules([
//         'username' => ['required', 'min:3', 'max:32']
//     ]);
// });
