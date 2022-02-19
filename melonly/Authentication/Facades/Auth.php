<?php

namespace Melonly\Authentication\Facades;

use Melonly\Authentication\Authenticator;
use Melonly\Container\Facade;

/**
 * @method static bool login(string $email, string $password)
 * @method static void logout()
 * @method static bool logged()
 * @method static \App\Models\User user()
 * @method static void setUserData(array $data)
 */
class Auth extends Facade
{
    protected static function getAccessor(): string
    {
        return Authenticator::class;
    }

    public static function __callStatic(string $method, array $args): mixed
    {
        return self::handleCall($method, $args, self::getAccessor());
    }
}
