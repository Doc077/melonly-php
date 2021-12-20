<?php

namespace App\Controllers;

use Melonly\Http\Request;
use Melonly\Http\Response;
use Melonly\Routing\Attributes\Route;

class LoginController {
    #[Route(path: '/login', class: self::class)]
    public function index(Request $request, Response $response) {
        $response->send('IT WORKS');
        echo'WORKS PARTIALLY';
    }

    #[Route(path: '/register', method: 'POST', class: self::class)]
    public function register() {
        
    }
}
