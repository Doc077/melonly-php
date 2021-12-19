<?php

namespace Melonly\Authentication;

use Melonly\Database\DB;

class Auth {
    public static function login(string $email, string $password): bool {
        $user = DB::query("SELECT * FROM `users` WHERE `email` = '$email'");

        if (count($user) > 0 && password_verify($password, $user['password'])) {
            $_SESSION['MELONLY_LOGGED'] = true;

            // $_SESSION['MELONLY_USER'] = new User($user);

            redirect('/');

            return true;
        }

        $_SESSION['MELONLY_LOGGED'] = false;

        redirect('/login');

        return false;
    }

    public static function logout(): void {
        session_unset();
        session_destroy();

        redirect('/login');
    }

    public static function logged(): bool {
        if (isset($_SESSION['MELONLY_LOGGED']) && $_SESSION['MELONLY_LOGGED'] === true) {
            return true;
        }

        return false;
    }

    public static function user(): mixed {
        if (isset($_SESSION['MELONLY_USER'])) {
            return $_SESSION['MELONLY_USER'];
        }

        return null;
    }
}
