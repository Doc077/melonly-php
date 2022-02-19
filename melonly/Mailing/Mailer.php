<?php

namespace Melonly\Mailing;

use Melonly\Interfaces\MailInterface;

class Mailer implements MailerInterface
{
    public function send(string $to, string $subject, string|MailInterface $message, bool $wrap = false, int $wrapAfter = 72): bool
    {
        if ($message instanceof MailInterface) {
            $message = $message->send();
        }

        if ($wrap) {
            $message = wordwrap($message, $wrapAfter);
        }

        $headers = 'From: ' . config('mail.address') . PHP_EOL . 'Reply-To: ' . config('mail.address') . PHP_EOL;

        return mail($to, $subject, $message, $headers);
    }
}
