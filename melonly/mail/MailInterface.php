<?php

namespace Melonly\Mailing;

interface MailInterface {
    public static function send(string $to, string $subject, string $message): void;
}
