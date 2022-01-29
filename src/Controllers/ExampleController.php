<?php

namespace App\Controllers;

use Melonly\Http\Request;
use Melonly\Http\Response;
use Melonly\Routing\Attributes\Route;

class ExampleController {
    #[Route(path: '/login')]
    public function index(Request $request, Response $response): void {
        $response->send('Login page');
    }
}
