<?php

namespace App\Controllers;

use Melonly\Http\Controller;
use Melonly\Http\Request;
use Melonly\Http\Response;

class ExampleController extends Controller
{
    public function index(Request $request, Response $response): void
    {
        $parameter = $request->parameter('slug');

        $response->send('Example route | URI slug parameter: ', $parameter);
    }
}
