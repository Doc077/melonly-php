<?php

namespace App\Controllers;

use Melonly\Authentication\Auth;
use Melonly\Http\Method as HttpMethod;
use Melonly\Http\Request;
use Melonly\Http\Response;
use Melonly\Routing\Attributes\Route;

class AuthController {
    /**
     * Show user login page.
     */
    #[Route(path: '/login')]
    public function index(Request $request, Response $response): void {
        $response->send('Login page');
    }

    /**
     * Authentiate user by provided credentials.
     */
    #[Route(path: '/login', method: HttpMethod::Post)]
    public function login(Request $request, Response $response): void {
        $email = $request->getField('email');
        $password = $request->getField('password');

        if (Auth::login($email, $password)) {
            $response->redirect('/');
        }
    }
}