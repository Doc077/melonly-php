<?php

namespace Melonly\Authentication;

use App\Models\User;
use Melonly\Database\Facades\DB;
use Melonly\Encryption\Hash;
use Melonly\Http\Session;

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
        if (count($user) > 0 && Hash::check($password, $user->password)) {
            Session::set('MELONLY_AUTHENTICATED', true);
            Session::set('MELONLY_AUTH_USER_DATA', get_object_vars($user));

            foreach (get_object_vars($user) as $field => $value) {
                self::$userData[$field] = $value;
            }

            return true;
        }

        Session::set('MELONLY_AUTHENTICATED', false);

        redirect('/login');

        return false;
    }

    public static function logout(): void {
        Session::clear();

        redirect('/login');
    }

    public static function logged(): bool {
        if (Session::isSet('MELONLY_AUTHENTICATED') && Session::get('MELONLY_AUTHENTICATED') === true) {
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
