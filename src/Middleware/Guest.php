<?php

namespace App\Middleware;

use Melonly\Authentication\Facades\Auth;
use Melonly\Http\Response;

class Guest
{
    public function handle(Response $response): void
    {
        if (Auth::logged()) {
            $response->redirect('/');
        }
    }
}
