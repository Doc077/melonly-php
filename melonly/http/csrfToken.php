<?php

if (!function_exists('csrfToken')) {
    function csrfToken(): string {
        if (!Session::isSet('MELONLY_CSRF_TOKEN')) {
            throw new TokenNotSetException('CSRF token is not set');
        }

        return Session::get('MELONLY_CSRF_TOKEN');
    }
}
