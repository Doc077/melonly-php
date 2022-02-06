<?php

namespace App\Controllers;

use Melonly\Http\Request;
use Melonly\Http\Response;

class ExampleController {
    public function index(Request $request, Response $response): void {
        $id = $request->parameter('id');

        $response->send('Example route. Slug parameter: ', $id);
    }
}
