<?php

namespace App\Controllers;

use Melonly\Authentication\Auth;
use Melonly\Http\Method as HttpMethod;
use Melonly\Http\Request;
use Melonly\Http\Response;
use Melonly\Routing\Attributes\Route;

class ExampleAuthController {
    /**
     * Show user login page.
     */
    #[Route(path: '/login')]
    public function index(Request $request, Response $response) {
        $response->send('Login route');
    }

    /**
     * Authentiate user by provided credentials.
     */
    #[Route(path: '/login', method: HttpMethod::Post)]
    public function register(Request $request, Response $response) {
        $email = $request->getField('email');
        $password = $request->getField('password');

        if (Auth::login($email, $password)) {
            $response->redirect('/');
        }
    }
}
