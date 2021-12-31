<?php

namespace App\Controllers;

use Melonly\Http\Request;
use Melonly\Http\Response;
use Melonly\Http\Method as HttpMethod;
use Melonly\Routing\Attributes\Route;

class AuthController {
    #[Route(path: '/login')]
    public function index(Request $request, Response $response) {
        $response->send('LOGIN');

        return 'login route';
    }

    #[Route(path: '/register', method: HttpMethod::Post)]
    public function register(Response $response) {
        $response->send('REGISTER');

        return 'register route';
    }
}
