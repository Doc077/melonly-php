<?php

namespace App\Controllers;

use Melonly\Http\Request;
use Melonly\Http\Response;
use Melonly\Http\Method as HttpMethod;
use Melonly\Routing\Attributes\Route;

class LoginController {
    #[Route(path: '/login', class: self::class)]
    public function index(Request $request, Response $response) {
        echo'WORKS PARTIALLY';
        $response->send('IT WORKS');
        return 6;
    }

    #[Route(path: '/register', method: HttpMethod::Post, class: self::class)]
    public function register() {
        // 
        return 7;
    }
}
