<?php

namespace Melonly\Authentication;

use Melonly\Database\DB;

class Auth {
    public static array $userData = [];

    public static function login(string $email, string $password): bool {
        if (self::logged()) {
            return false;
        }

        $user = DB::query("SELECT * FROM `users` WHERE `email` = '$email'");

        /**
         * Validate password hash.
         */
        if (count($user) > 0 && password_verify($password, $user->password)) {
            $_SESSION['MELONLY_AUTHENTICATED'] = true;
            $_SESSION['MELONLY_AUTH_USER_DATA'] = get_object_vars($user);

            foreach (get_object_vars($user) as $field => $value) {
                self::$userData[$field] = $value;
            }

            return true;
        }

        $_SESSION['MELONLY_AUTHENTICATED'] = false;

        redirect('/login');

        return false;
    }

    public static function logout(): void {
        session_unset();
        session_destroy();

        redirect('/login');
    }

    public static function logged(): bool {
        if (isset($_SESSION['MELONLY_AUTHENTICATED']) && $_SESSION['MELONLY_AUTHENTICATED'] === true) {
            return true;
        }

        return false;
    }

    public static function user(): User {
        $authUser = new User();

        foreach (self::$userData as $field => $value) {
            $authUser->{$field} = $value;
        }

        return $authUser;
    }
}
