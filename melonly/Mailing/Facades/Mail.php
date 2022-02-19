<?php

namespace Melonly\Mailing\Facades;

use Melonly\Container\Facade;

/**
 * @method static bool send(string $to, string $subject, string|MailInterface $message, bool $wrap = false, int $wrapAfter = 72)
 */
class Mail extends Facade
{
    protected static function getAccessor(): string
    {
        return Mailer::class;
    }

    public static function __callStatic(string $method, array $args): mixed
    {
        return self::handleCall($method, $args, self::getAccessor());
    }
}
