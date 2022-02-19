<?php

namespace Melonly\Mailing;

interface MailerInterface
{
    public function send(string $to, string $subject, string $message): bool;
}
