<?php

use Melonly\Routing\Router;
use Melonly\Http\Request;
use Melonly\Http\Response;
use Melonly\Validation\Validator;
use App\Models\User;

echo vector(1, 2, 3)->map(fn ($item, $i) => $item + $i)[1];
Router::get('/', function (Request $request, Response $response): void {
    $user = User::where('id', '=', 1)->orWhere('age', '>', 10)->fetch();

    $response->view('pages.home', [
        'name' => $user->name
    ]);
});

// Router::post('/login', function (Request $request, Response $response): void {
//     Validator::check([
//         'username' => ['required', 'min:3', 'max:32']
//     ]);
// });
